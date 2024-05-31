<?php

declare(strict_types=1);

use VPremiss\Crafty\Enums\DataType;
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

it('has a decent array validation method', function () {
    expect(Crafty::validatedArray([1, 2, 3], DataType::Integer))->toBeTrue();
    expect(Crafty::validatedArray([1, 2, 'c'], DataType::Integer))->toBeFalse();
    expect(Crafty::validatedArray(['a', '2', 'b'], DataType::String))->toBeTrue();
    expect(Crafty::validatedArray(['a', '2', 22], DataType::String))->toBeFalse();
    expect(Crafty::validatedArray([[], [], []], DataType::Array))->toBeTrue();
    expect(Crafty::validatedArray([[], [], false], DataType::Array))->toBeFalse();

    expect(Crafty::validatedArray([2 => 3.8, 3 => 2.77, 4 => 1.0], DataType::Integer, DataType::Float))->toBeTrue();
    expect(Crafty::validatedArray(['a' => 3.8, 'b' => 2.77, 'z' => 1.0], DataType::Integer, DataType::Float))->toBeFalse();
    expect(
        Crafty::validatedArray(
            ['something' => fn () => 'something', 'another' => fn () => 'another'],
            DataType::String,
            DataType::Closure,
        ),
    )->toBeTrue();
    expect(
        Crafty::validatedArray(
            ['something' => 'something', 'another' => fn () => 'another'],
            DataType::String,
            DataType::Closure,
        ),
    )->toBeFalse();
    expect(
        Crafty::validatedArray(
            ['an-option' => true, 'no-option' => false],
            DataType::String,
            DataType::Boolean,
        ),
    )->toBeTrue();
    expect(
        Crafty::validatedArray(
            ['an-option' => true, 'no-option' => 1],
            DataType::String,
            DataType::Boolean,
        ),
    )->toBeFalse();

    expect(
        Crafty::validatedArray(
            [
                'randomness' => [
                    'glitches' => false,
                    'different-humane-needs' => false,
                    'indistinguishable-creature-origins' => false,
                    'non-human-dominating-creatures' => false,
                ],
                'fate' => [
                    'aging' => true,
                    'same-humane-desires' => true,
                    'companionship' => true,
                    'death' => true,
                ],
            ],
            DataType::String,
            fn ($innerArray) => Crafty::validatedArray($innerArray, DataType::String, DataType::Boolean),
        ),
    )->toBeTrue();
    expect(
        Crafty::validatedArray(
            [
                'arabicable.special_characters' => [
                    'harakat' => 1,
                    'indian_numerals' => 1,
                    'arabic_numerals' => 1,
                    'punctuation_marks' => 1,
                    'foreign_punctuation_marks' => 1,
                    'arabic_punctuation_marks' => 1,
                    'enclosing_marks' => 1,
                    'enclosing_starter_marks' => 1,
                    'enclosing_ender_marks' => 1,
                    'arabic_enclosing_marks' => 1,
                    'arabic_enclosing_starter_marks' => 1,
                    'arabic_enclosing_ender_marks' => 1,
                ],
            ],
            DataType::String,
            fn ($innerArray) => Crafty::validatedArray($innerArray, DataType::String, DataType::Boolean),
        ),
    )->toBeFalse();
});
