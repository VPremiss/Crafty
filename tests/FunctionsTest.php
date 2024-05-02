<?php

declare(strict_types=1);

it('has a validation is_enum function', function () {
    class SomeClass
    {
    }

    expect(is_enum(new SomeClass))->toBeFalse();
    expect(is_enum(null))->toBeFalse();
    expect(is_enum(''))->toBeFalse();
    expect(is_enum('Hi'))->toBeFalse();
    expect(is_enum(74))->toBeFalse();

    enum SomeEnum: string
    {
        case Test = 'TEST';
    }

    expect(is_enum(SomeEnum::Test))->toBeTrue();
});

it('has a generation unique_meta_hashing_number function', function () {
    $results = [];

    for ($i = 0; $i < 100; $i++) {
        $result = unique_meta_hashing_number('test_string');

        expect($results)->not->toContain($result);

        $results[] = $result;
    }
});
