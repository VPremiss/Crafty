<?php

declare(strict_types=1);

namespace VPremiss\Crafty\Utilities\Installable\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use VPremiss\Crafty\Utilities\Installable\Interfaces\Installable;
use VPremiss\Crafty\Utilities\Installable\Support\Exceptions\InstallableInterfaceException;

// ? A package-tools service provider's
trait HasInstallationCommand
{
    // ? Use in the bootingPackage method
    public function installationCommand(): void
    {
        $serviceProvider = $this;

        Artisan::command("{$this->package->shortName()}:install", function () use ($serviceProvider) {
            $this->hidden = true;

            $this->comment('Installing the package...');

            if (!$this instanceof Installable) {
                throw new InstallableInterfaceException(
                    'The package service provider must implement Crafty\'s Installable interface.'
                );
            }

            // * =========================
            // * Publishing configuration
            // * =======================
            
            $this->call('vendor:publish', ['--tag' => "{$serviceProvider->package->shortName()}-config"]);

            // * ======================
            // * Publishing migrations
            // * ====================
            
            $this->call('vendor:publish', ['--tag' => "{$serviceProvider->package->shortName()}-migrations"]);

            // * =========================
            // * Prompt to run migrations
            // * =======================
            
            if ($this->confirm('Shall we proceed to run the migrations?', true)) {
                $this->info('Running migrations...');

                $this->call('migrate');

                $this->info('Migrations are done.');
            }

            if (!app()->environment('testing')) {

                // * ===================
                // * Publishing seeders
                // * =================

                $seederFilePaths = $serviceProvider->seederFilePaths();
                $aSeederWasNotFound = false;

                foreach ($seederFilePaths as $path) {
                    if (!File::exists($path)) {
                        $aSeederWasNotFound = true;
                        break;
                    } else {
                        $seederFilePaths = array_combine($seederFilePaths, [
                            $path => database_path(str($path)->after('database/')->value()),
                        ]);
                    }
                }

                if (!$aSeederWasNotFound) {
                    $serviceProvider->publishes($seederFilePaths, "{$serviceProvider->package->shortName()}-seeders");

                    $this->call('vendor:publish', ['--tag' => "{$serviceProvider->package->shortName()}-seeders"]);
                }

                if (!$aSeederWasNotFound) {
                    
                    // * ======================
                    // * Prompt to run seeders
                    // * ====================
                    
                    if ($this->confirm('Shall we run the seeders too?', true)) {
                        foreach ($seederFilePaths as $_ => $path) {
                            // * Correct the namespace if necessary
                            $seederContent = File::get($path);
                            $newSeederContent = preg_replace(
                                '/namespace\s+\w+\\\(\w+)\\\Database\\\Seeders;/', // ? getting rid of Vendor/PackageName
                                'namespace Database\Seeders;',
                                $seederContent
                            );
                            File::put($path, $newSeederContent);
            
                            // * Seed
                            $this->call('db:seed', [
                                '--class' => str($path)->after('seeders/')->before('.php')->value(),
                                '--force' => true
                            ]);
                        }
                    }
                    
                    // * ===================================
                    // * Add seeders to DatabaseSeeder file
                    // * =================================
                    
                    if (File::exists($databaseSeederPath = database_path("seeders/DatabaseSeeder.php"))) {
                        $fileContents = File::get($databaseSeederPath);
                        $addedClasses = [];
            
                        foreach ($seederFilePaths as $_ => $path) {
                            $className = str($path)->after('seeders/')->before('.php')->value();
                            $seederClassStatement = "\$this->call({$className}::class);";
            
                            if (!in_array($className, $addedClasses) && strpos($fileContents, $seederClassStatement) === false) {
                                $searchPattern = '/public function run\(\)(\s*):?\s*void\s*{\s*/';
                                $replacePattern = "$0\n        $seederClassStatement";
                                $fileContents = preg_replace($searchPattern, $replacePattern, $fileContents);
                                $addedClasses[] = $className;
                            }
                        }
            
                        File::put($databaseSeederPath, $fileContents);
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
            
            if ($this->confirm('Would you kindly star our package on GitHub?', true)) {
                $packageUrl = "https://github.com/vpremiss/{$serviceProvider->package->shortName()}";
    
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
        });
    }
}
