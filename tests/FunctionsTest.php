<?php

declare(strict_types=1);

it('has a quick validation is_filled_string function', function () {
    expect(
        is_filled_string('')
    )->toBeFalse();
    expect(
        is_filled_string('         ')
    )->toBeFalse();
    expect(
        is_filled_string(1)
    )->toBeFalse();
    expect(
        is_filled_string(1.5)
    )->toBeFalse();
    expect(
        is_filled_string(fn () => null)
    )->toBeFalse();
    expect(
        is_filled_string([])
    )->toBeFalse();

    expect(
        is_filled_string('1')
    )->toBeTrue();
    expect(
        is_filled_string('abc')
    )->toBeTrue();
});

it('has a neat validation function for detecting associative arrays', function () {
    expect(is_associative_array([1, 2, 3]))->toBeFalse();
    expect(is_associative_array(['a', 'b', 'c']))->toBeFalse();
    expect(is_associative_array([0 => 'a', 1 => 'b', 2 => 'c']))->toBeFalse();

    expect(is_associative_array([1 => 'a', 2 => 'b', 3 => 'c']))->toBeTrue();
    expect(is_associative_array(['a' => 1, 'b' => 2, 'c' => 3]))->toBeTrue();
    expect(
        is_associative_array(
            [
                'a' => [
                    1, 2, 3,
                ],
            ],
        ),
    )->toBeTrue();
});

it('has a validation is_enum function', function () {
    class SomeClass
    {
    }

    expect(is_enum(new SomeClass))->toBeFalse();
    expect(is_enum(null))->toBeFalse();
    expect(is_enum(''))->toBeFalse();
    expect(is_enum('Hi'))->toBeFalse();
    expect(is_enum(74))->toBeFalse();

    enum NewEnum: string
    {
        case Test = 'test';
    }

    expect(is_enum(NewEnum::Test))->toBeTrue();
    
    enum YetAnotherEnum: string
    {
        case AlsoTest = 'also-test';
    }

    expect(is_enum(NewEnum::Test, YetAnotherEnum::class))->toBeFalse();
    expect(is_enum(YetAnotherEnum::AlsoTest, YetAnotherEnum::class))->toBeTrue();
});

it('has a generation unique_meta_hashing_number function', function () {
    $results = [];

    for ($i = 0; $i < 100; $i++) {
        $result = unique_meta_hashing_number('test_string');

        expect($results)->not->toContain($result);

        $results[] = $result;
    }
});
