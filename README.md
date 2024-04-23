<div align="center">
    بسم الله الرحمن الرحيم
</div>

# Crafty

Some essential helpers to rely on during [TALL stack](https://tallstack.dev) development.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vpremiss/crafty.svg?style=for-the-badge)](https://packagist.org/packages/vpremiss/crafty)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vpremiss/crafty/run-tests.yml?branch=main&label=tests&style=for-the-badge&color=forestgreen)](https://github.com/vpremiss/crafty/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vpremiss/crafty.svg?style=for-the-badge&color=b07d00)](https://packagist.org/packages/vpremiss/crafty)


## Description

Contains a helper main service class (that you can use as a [Laravel facade](https://laravel.com/docs/facades)). Plus some global helper functions for quick actions as well... For the things that we find ourselves needing and without having a "strong" reason to PR Laravel about.


## Installation

- Install the package via [composer](https://getcomposer.org):

  ```bash
  composer require VPremiss/Crafty
  ```

- Publish the [config file](config/crafty.php) using this [Artisan](https://laravel.com/docs/artisan) command:

  ```bash
  php artisan vendor:publish --tag="crafty-config"
  ```


## API

Below are the tables of all the `Crafty` package helpers:

<br/>

| **Interface**          | Description                                                                               |
|----------------|-------------------------------------------------------------------------------------------|
| `Configurated`  | Ensures that the package service provider is dealing with configuration [internally](src/CraftyServiceProvider.php#26). -Making it compatible to be used in other packages and tests!    |

<br/>

| **Trait**          | Description                                                                               |
|----------------|-------------------------------------------------------------------------------------------|
| `Enumerified`  | Extends enum functionality to retrieve counts, random instances, and enum collections.    |

<br/>

| **Laravel Rule**         | Description                                                                                               |
|--------------|-----------------------------------------------------------------------------------------------------------|
| `EnumsArray` | A validation rule that ensures an attribute is a filled array of valid enum values from a specified class.|

<br/>

| **Crafty Facade Method**                                                              | Description                                                                                             |
|---------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------|
| `chunkedDatabaseInsertion(string $tableName, array $dataArrays, Closure $callback): void` | Handles database insertions in chunks with configurable chunk size and default properties.             |
| `uniquelyMetaHashSuffixed(string $string): string`                          | Appends a unique hash suffix to a string. Utilizes the global helper function `unique_meta_hashing_number` of this same package.                                                               |
| `reverseString(string $string, EncodingType $encoding = EncodingType::UTF8): string` | Reverses a string according to the specified encoding type.                                             |

<br/>

| **Global Function**              | Description                                                                |
|-----------------------|----------------------------------------------------------------------------|
| `is_enum(mixed $enum)`| Checks if the provided value is an instance of an enum.                    |
| `unique_meta_hashing_number(string $string, ?int $digits = null)` | Generates a unique hash number based on the input string and optional digit limit. **Not for security purposes**, merely for general meta information tagging. |
| `validated_config(string $packageServiceProviderNamespace, string $configKey)` | Return the package configuration value gracefully. Still, you **must** implement [Configurated](src/Utilities/Configurated/Interfaces/Configurated.php) interface methods properly. |

<br/>


### Changelogs

You can check out the package's [changelogs](https://app.whatthediff.ai/changelog/github/VPremiss/Crafty) online via WTD.


## Support

Support ongoing package maintenance as well as the development of **other projects** through [sponsorship](https://github.com/sponsors/VPremiss) or one-time [donations](https://github.com/sponsors/VPremiss?frequency=one-time&sponsor=VPremiss) if you prefer.

And may Allah accept your strive; aameen.

### License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

### Credits

- [ChatGPT](https://chat.openai.com)
- [Laravel](https://github.com/Laravel)
- [Spatie](https://github.com/spatie)
- [Graphite](https://graphite.dev)
- [WTD](https://whatthediff.ai)
- [All Contributors](../../contributors)
- And the generous individuals that we've learned from and been supported by throughout our journey...


<div align="center">
   <br>والحمد لله رب العالمين
</div>
