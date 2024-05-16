<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use VPremiss\Crafty\Utilities\Enumerified\Support\Exceptions\EnumerifiedEnumException;
use VPremiss\Crafty\Utilities\Enumerified\Traits\Enumerified;

enum AnEnum: string
{
    use Enumerified;

    case Test = 'test';
    case Tester = 'tester';
    case Tested = 'tested';
}

enum TranslatedEnum: string
{
    use Enumerified;

    case Home = 'home';
    case About = 'about';

    public function translated(string $locale = null): string
    {
        return match ($this) {
            TranslatedEnum::Home => __('HomeTranslationKey', locale: $locale),
            TranslatedEnum::About => __('AboutTranslationKey', locale: $locale),
        };
    }
}

describe('Enumerfied trait', function () {
    test('can count enum cases directly', function () {
        expect(AnEnum::count())->toBe(3);
    });

    test('can get the first enum case quickly', function () {
        expect(AnEnum::first())->toBe(AnEnum::Test);
    });

    test('can get a random amount of enum cases as an array', function () {
        $array = AnEnum::random(2, exceptFor: AnEnum::Test);

        expect(count($array))->toBe(2);
        expect($array)->toEqualCanonicalizing([AnEnum::Tester, AnEnum::Tested]);
    });

    test('can get one random enum case and optionally wrapped in an array', function () {
        $random = AnEnum::random();

        expect($random)->toBeIn(AnEnum::cases());

        $random = AnEnum::random(exceptFor: [AnEnum::Test, AnEnum::Tested], asArray: true);

        expect($random)->toBe([AnEnum::Tester]);
    });

    test('can get enum case names quickly', function () {
        expect(AnEnum::names(exceptFor: AnEnum::Test))->toEqualCanonicalizing([
            AnEnum::Tester->name,
            AnEnum::Tested->name,
        ]);
    });

    test('can get enum case values quickly', function () {
        expect(AnEnum::values(exceptFor: AnEnum::Test))->toEqualCanonicalizing([
            AnEnum::Tester->value,
            AnEnum::Tested->value,
        ]);
    });

    test('can make enum deal with translations easily', function () {
        expect(TranslatedEnum::Home->translated())->toBe('HomeTranslationKey');

        expect(TranslatedEnum::random(exceptFor: TranslatedEnum::About, translated: true))->toBe(
            ['HomeTranslationKey' => 'home'],
        );

        expect(TranslatedEnum::random(2, translated: true))->toEqualCanonicalizing([
            'HomeTranslationKey' => 'home',
            'AboutTranslationKey' => 'about',
        ]);

        expect(TranslatedEnum::names(translated: true))->toEqualCanonicalizing([
            'HomeTranslationKey',
            'AboutTranslationKey',
        ]);

        expect((TranslatedEnum::collection(translated: true))->toArray())->toEqualCanonicalizing([
            'HomeTranslationKey' => 'home',
            'AboutTranslationKey' => 'about',
        ]);

        expect(AnEnum::collection(translated: true))->toThrow(
            EnumerifiedEnumException::class,
            "There isn't a 'translated' method on the enum that returns a matching case value string. So create one!",
        );
    })->throws(EnumerifiedEnumException::class);

    test('can get enum cases as collection real quick', function () {
        expect(AnEnum::collection())->toBeInstanceOf(Collection::class);
    })->throws(EnumerifiedEnumException::class);
});
