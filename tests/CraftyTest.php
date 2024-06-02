<?php

declare(strict_types=1);

use VPremiss\Crafty\Enums\DataType;
use VPremiss\Crafty\Facades\Crafty;
use VPremiss\Crafty\Support\Exceptions\CraftyFilteringException;
use Workbench\App\Models\Person;
use Workbench\Database\Seeders\PersonSeeder;

use function Pest\Laravel\seed;

describe('CraftilyHandlesDatabase trait → chunkedDatabaseInsertion function', function () {
    it('handles performant database customizable insertion through chunking', function () {
        seed(PersonSeeder::class);

        expect(Person::count())->toBe(2); // ? Check the seeder
    });

    // TODO test failure
});

describe('CraftilyHandlesFiltering trait → filterProps function', function () {
    it("filters for 'only' and 'except' criteria or defaulting to whatever 'all' is", function () {
        $array = ['1', '2', 'threeeeee'];

        expect(Crafty::filterProps($array, '2'))->toBe(['2']);
        expect(Crafty::filterProps($array, except: 'threeeeee'))->toBe(['1', '2']);
        expect(Crafty::filterProps($array))->toBe(['1', '2', 'threeeeee']);

        expect(Crafty::filterProps(collect($array), except: ['1', '2']))->toBe(['threeeeee']);
    });

    it("throws when both 'only' and 'except' are used", function () {
        $array = ['1', '2', 'threeeeee'];

        expect(Crafty::filterProps($array, '2', 'three'))->toThrow(
            CraftyFilteringException::class,
            "You shouldn't use both `except` and `only` arguments, only one of them.",
        );
    })->throws(CraftyFilteringException::class);
});

describe('CraftilyHandlesGeneration trait → uniquelyMetaHashSuffixed function', function () {
    it('appends a unique hash to strings', function () {
        $results = [];
    
        for ($i = 0; $i < 100; $i++) {
            $result = Crafty::uniquelyMetaHashSuffixed('test_string');
    
            expect($results)->not->toContain($result);
    
            $results[] = $result;
    
            expect($results[array_rand($results)])->toContain('test_string');
        }
    });
});

// TODO group
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

// TODO group
it('has a decent array validation method', function () {
    expect(Crafty::validatedArray([1, 2, 3], DataType::Integer))->toBeTrue();
    expect(Crafty::validatedArray([1, 2, 'c'], DataType::Integer))->toBeFalse();
    expect(Crafty::validatedArray(['a', '2', 'b'], DataType::String))->toBeTrue();
    expect(Crafty::validatedArray(['a', '2', 22], DataType::String))->toBeFalse();
    expect(Crafty::validatedArray([' ', '  ', '   '], DataType::FilledString))->toBeFalse();
    expect(Crafty::validatedArray([' .', '  .', '   .'], DataType::FilledString))->toBeTrue();
    expect(Crafty::validatedArray([[], [], []], DataType::Array))->toBeTrue();
    expect(Crafty::validatedArray([[], [], false], DataType::Array))->toBeFalse();

    enum SomeEnum: string
    {
        case Test = 'test';
        case Tester = 'tester';
    }

    enum AnotherEnum: string
    {
        case AlsoTesting = 'also-testing';
    }

    expect(Crafty::validatedArray([SomeEnum::Test, SomeEnum::Tester], DataType::Enum))->toBeTrue();
    expect(Crafty::validatedArray([SomeEnum::Test, SomeEnum::Tester], DataType::SpecificEnum(AnotherEnum::class)))->toBeFalse();
    expect(Crafty::validatedArray([SomeEnum::Test, SomeEnum::Tester], DataType::SpecificEnum(SomeEnum::class)))->toBeTrue();

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
