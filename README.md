# Unit Test Suite
PHPUnit test suit helps you with mocking several craft services and classes.

It enable you to:

- Configure any abstract classes you wish to load before running unit tests
- Save time by not having to manually mock Craft's database models and it's required classes
- Simply override or add extra methods to the mocked classes mocked in UnitTestSuite_AbstractTest

## Requirements:
- PHP 5.4+
- Craft 2.4+

## Installation:
The library can be installed using Composer.
```   
composer require nerds-and-company/unit-test-suite ~1.0.0
```

## Classes:
The library is composed of two classes:

- `UnitTestSuite_AbstractTest` is the abstract class you can extend that contains the mocked Craft database classes
- `AbstractTestLoader` is used to load custom abstract classes that for example extend UnitTestSuite_AbstractTest and add extra functionality

## Usage

- Make sure the phpunit bootstrap points at `bootstrap="vendor/unittestsuite/bootstrap.php`
- Copy the `unittestsuite.yml` supplied with this package `/craft/config` and add extra (abstract) classes you wish to pre-load
- Extend `UnitTestSuite_AbstractTest` and override or add extra data to the returned mocks