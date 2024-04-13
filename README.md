<div align="center">
    بسم الله الرحمن الرحيم
</div>

# Crafty

Some essentials to rely on for [TALL stack](https://tallstack.dev) development.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vpremiss/crafty.svg?style=flat-square)](https://packagist.org/packages/vpremiss/crafty)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vpremiss/crafty/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/vpremiss/crafty/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/vpremiss/crafty/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/vpremiss/crafty/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vpremiss/crafty.svg?style=flat-square)](https://packagist.org/packages/vpremiss/crafty)


## Description

Contains a helper main service class with separated concerns for its static methods. Plus a global functions for quick helpers as well... The things that we find ourselves needing and not have a "strong" reason to PR [Laravel](https://laravel.com) about.


## Installation

- Install the package via [composer](https://getcomposer.org):

  ```bash
  composer require vpremiss/crafty
  ```

- Publish the [config file](config/crafty.php) using this Artisan command:

  ```bash
  php artisan vendor:publish --tag="crafty-config"
  ```


## API

Below are the tables of all the `Crafty` package helpers:

<br/>

| **Trait**          | Description                                                                               |
|----------------|-------------------------------------------------------------------------------------------|
| `Enumerified`  | Extends enum functionality to retrieve counts, random instances, and enum collections.    |

<br/>

| **Laravel Rule**         | Description                                                                                               |
|--------------|-----------------------------------------------------------------------------------------------------------|
| `EnumsArray` | A validation rule that ensures an attribute is a filled array of valid enum values from a specified class.|

<br/>

| **Facade Method**                                                              | Description                                                                                             |
|---------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------|
| `chunkedDatabaseInsertion(string $tableName, array $dataArrays, Closure $callback): void` | Handles database insertions in chunks with configurable chunk size and default properties.             |
| `uniquelySuffixed(string $string): string`                          | Appends a unique hash suffix to a string.                                                               |
| `reverseString(string $string, EncodingType $encoding = EncodingType::UTF8): string` | Reverses a string according to the specified encoding type.                                             |

<br/>

| **Global Function**              | Description                                                                |
|-----------------------|----------------------------------------------------------------------------|
| `is_enum(mixed $enum)`| Checks if the provided value is an instance of an enum.                    |
| `unique_meta_hashing_number(string $string, ?int $digits = null)` | Generates a unique hash number based on the input string and optional digit limit. **Not for security purposes**, merely for general meta information tagging. |

<br/>


### Changelogs

You can check out the [[CHANGELOG.md]](CHANGELOG.md) file to track down all the package updates.


## Support

Support the maintenance as well as the development of [other projects](https://github.com/sponsors/VPremiss) through sponsorship or one-time [donations](https://github.com/sponsors/VPremiss?frequency=one-time&sponsor=VPremiss).

### License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

### Credits

- [ChatGPT](https://chat.openai.com)
- [Laravel](https://github.com/Laravel)
- [All Contributors](../../contributors)


<div align="center">
   <br>والحمد لله رب العالمين
</div>
