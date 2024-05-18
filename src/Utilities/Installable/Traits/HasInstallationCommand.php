<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Installable\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use VPremiss\Crafty\Utilities\Installable\Enums\AssetType;
use VPremiss\Crafty\Utilities\Installable\Interfaces\Installable;
use VPremiss\Crafty\Utilities\Installable\Support\Exceptions\InstallableInterfaceException;

use function Orchestra\Testbench\workbench_path;

// ? A package-tools service provider's
trait HasInstallationCommand
{
    public function packageShortName(): string
    {
        return $this->package->shortName();
    }

    public function packagePublishes(array $paths, $tag): void
    {
        $this->publishes($paths, $tag);
    }

    public function getPackageNamespace(): string
    {
        return (new ReflectionClass($this))->getNamespaceName();
    }

    public function copyToWorkbenchSkeleton(AssetType $type): void
    {
        $directory = match ($type) {
            AssetType::Config => 'config',
            AssetType::Migration => 'database/migrations',
            AssetType::Seeder => 'database/seeders',
        };

        $workbenchSkeletonPath = workbench_path();
        $publishedPath = base_path("vendor/orchestra/testbench-core/laravel/{$directory}");
        $destinationPath = "{$workbenchSkeletonPath}/{$directory}";

        if (File::exists($publishedPath)) {
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $files = Finder::create()->files()->in($publishedPath);

            foreach ($files as $file) {
                $destFilePath = $destinationPath . DIRECTORY_SEPARATOR . $file->getRelativePathname();
                File::copy($file->getRealPath(), $destFilePath);
            }
        }
    }

    // ? Apply in the bootingPackage method
    public function installationCommand(): void
    {
        $serviceProvider = $this;

        if (!$serviceProvider instanceof Installable) {
            throw new InstallableInterfaceException(
                'The package service provider must implement Crafty\'s Installable interface.'
            );
        }

        Artisan::command("{$serviceProvider->packageShortName()}:install {--enforced}", function () use ($serviceProvider) {
            $this->hidden = true;

            $this->comment('Installing the package...');

            // * =========================
            // * Publishing configuration
            // * =======================

            $this->callSilently(
                'vendor:publish',
                $this->option('enforced')
                    ? [
                        '--tag' => "{$serviceProvider->packageShortName()}-config",
                        '--force',
                    ]
                    : ['--tag' => "{$serviceProvider->packageShortName()}-config"],
            );

            $serviceProvider->copyToWorkbenchSkeleton(AssetType::Config);

            $this->comment('Published the config file.');

            // * ======================
            // * Publishing migrations
            // * ====================

            $this->callSilently(
                'vendor:publish',
                $this->option('enforced')
                    ? [
                        '--tag' => "{$serviceProvider->packageShortName()}-migrations",
                        '--force',
                    ]
                    : ['--tag' => "{$serviceProvider->packageShortName()}-migrations"],
            );

            $serviceProvider->copyToWorkbenchSkeleton(AssetType::Migration);

            $this->comment('Published migration files.');

            // * =========================
            // * Prompt to run migrations
            // * =======================

            if ($this->option('enforced') || $this->confirm('Shall we proceed to run the migrations?', true)) {
                $this->comment('Running migrations...');

                $this->callSilently('migrate');

                $this->comment('Migrated successfully.');
            }

            if (!app()->environment('testing') || env('IN_CI', false)) {

                // * ===================
                // * Publishing seeders
                // * =================

                $seederFilePaths = $serviceProvider->seederFilePaths();
                $aSeederWasNotFound = false;

                $modifiedSeederFilePaths = [];
                foreach ($seederFilePaths as $path) {
                    if (!File::exists($path)) {
                        $aSeederWasNotFound = true;
                        break;
                    } else {
                        $modifiedSeederFilePaths[$path] = database_path(str($path)->after('database/')->value());
                    }
                }
                $seederFilePaths = $modifiedSeederFilePaths;

                if (!$aSeederWasNotFound) {
                    $serviceProvider->packagePublishes($seederFilePaths, "{$serviceProvider->packageShortName()}-seeders");

                    // ? Publishing now
                    $this->callSilently(
                        'vendor:publish',
                        $this->option('enforced')
                            ? [
                                '--tag' => "{$serviceProvider->packageShortName()}-seeders",
                                '--force',
                            ]
                            : ['--tag' => "{$serviceProvider->packageShortName()}-seeders"],
                    );

                    // ? Update the seeders' namespaces afterwards
                    foreach ($seederFilePaths as $path) {
                        $fileContents = File::get($path);
                        $correctNamespace = "namespace Database\Seeders;";
                        $namespacePattern = '/^namespace\s+([a-zA-Z0-9\\\]+);/m';

                        if (preg_match($namespacePattern, $fileContents, $matches)) {
                            File::put($path, preg_replace($namespacePattern, $correctNamespace, $fileContents));
                        }
                    }

                    $serviceProvider->copyToWorkbenchSkeleton(AssetType::Seeder);

                    $this->comment('Published seeder files.');

                    // * ======================
                    // * Prompt to run seeders
                    // * ====================

                    if ($this->option('enforced') || $this->confirm('Shall we run the seeders too?', true)) {
                        foreach ($seederFilePaths as $path) {
                            // * Seed
                            $this->comment('Running seeders.');

                            $namespace = $serviceProvider->getPackageNamespace();
                            $className = str($path)->after('seeders/')->before('.php')->value();
                            $className = "{$namespace}\\Database\\Seeders\\$className";

                            if (env('IN_CI', false)) {
                                require_once $path;

                                $seeder = new $className;
                                $seeder->run();
                            } else {
                                $this->callSilently('db:seed', [
                                    '--class' => $className,
                                    '--force' => true
                                ]);
                            }

                            $this->comment('Seeded successfully.');
                        }
                    }

                    // * ===================================
                    // * Add seeders to DatabaseSeeder file
                    // * =================================

                    if (File::exists($databaseSeederPath = database_path("seeders/DatabaseSeeder.php"))) {
                        $fileContents = File::get($databaseSeederPath);
                        $addedClasses = [];

                        foreach ($seederFilePaths as $path) {
                            $className = str($path)->after('seeders/')->before('.php')->value();
                            $seederClassStatement = "\$this->call({$className}::class);";

                            // ? Use a regular expression to find the exact place to insert the new seeder call
                            if (!in_array($className, $addedClasses) && strpos($fileContents, $seederClassStatement) === false) {
                                // ? This pattern accounts for the possible existing empty line
                                $pattern = '/(public function run\(\): void\s*{\s*\n)(\s*)/';

                                if (preg_match($pattern, $fileContents, $matches)) {
                                    // ? Capture the indentation level to maintain formatting consistency
                                    $indentation = $matches[2];
                                    $replacement = $matches[1] . $indentation . $seederClassStatement . "\n" . $indentation;

                                    $fileContents = preg_replace($pattern, $replacement, $fileContents, 1);
                                    $addedClasses[] = $className;
                                }
                            }
                        }

                        // ? Write all the seeders at once
                        File::put($databaseSeederPath, $fileContents);

                        $this->comment('Added seeder calls in DatabaseSeeder file.');
                    }
                } else {
                    $this->error('Seeders publishing failed.');
                }
            } else {
                $this->info("Skipping seeding in testing environment.");
            }

            // * =========================
            // * Prompt to star on Github
            // * =======================

            if ($this->confirm('Would you kindly star our package on GitHub?', $this->option('enforced') ? false : true)) {
                $packageUrl = "https://github.com/vpremiss/{$serviceProvider->packageShortName()}";

                if (PHP_OS_FAMILY == 'Darwin') {
                    exec("open {$packageUrl}");
                }
                if (PHP_OS_FAMILY == 'Windows') {
                    exec("start {$packageUrl}");
                }
                if (PHP_OS_FAMILY == 'Linux') {
                    exec("xdg-open {$packageUrl}");
                }
            }

            $this->comment('Arabicable installation complete.');
        });
    }
}
