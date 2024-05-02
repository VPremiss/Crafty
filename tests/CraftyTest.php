<?php

declare(strict_types=1);

use VPremiss\Crafty\Facades\Crafty;
use Workbench\App\Models\Person;
use Workbench\Database\Seeders\PersonSeeder;

use function Pest\Laravel\seed;

it('has a chunkedDatabaseInsertion method that handles exactly that', function () {
    seed(PersonSeeder::class);

    expect(Person::count())->toBe(2); // ? Check the seeder
});

it('has a uniquelyMetaHashSuffixed method that appends a unique hash to strings', function () {
    $results = [];

    for ($i = 0; $i < 100; $i++) {
        $result = Crafty::uniquelyMetaHashSuffixed('test_string');

        expect($results)->not->toContain($result);

        $results[] = $result;

        expect($results[array_rand($results)])->toContain('test_string');
    }
});

it('has a reverseString method that works for multibyte strings', function () {
    $arabicText = <<<Arabic
وإنّي وإنِّي ثم إنّي وإنَّنِي ... إذا انقطعت نعلي جعلت لها شسعا
Arabic;
    $reversedArabicText = <<<Arabic
اعسش اهل تلعج يلعن تعطقنا اذإ ... يِنَّنإو يّنإ مث يِّنإو يّنإو
Arabic;

    $result = Crafty::reverseString($arabicText);

    expect($result)->toBe($reversedArabicText);
});
