![phpat](.github/logo.png "phpat logo")

[![Latest Version](https://img.shields.io/packagist/v/carlosas/phpat.svg?style=flat-square)](https://packagist.org/packages/carlosas/phpat)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D_7.1-8892BF.svg?style=flat-square)](https://github.com/carlosas/phpat)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?style=flat-square&color=brightgreen)](LICENSE)
[![contributions](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat-square)](https://github.com/carlosas/phpat/issues)
![HitCount](http://hits.dwyl.com/carlosas/phpat.svg)

## Installation
```bash
composer require --dev carlosas/phpat
```

## Configuration
You might want to setup a basic configuration:
```yaml
# phpat.yml
files:
  src_path: src/
tests:
  path: tests/architecture/
```
This is the complete list of options:
* `files` `src_path`: The root path of your application.
* `files` `src_included`: Files you want to be tested excluding the rest (empty=all).
* `files` `src_excluded`: Files you want to be excluded in the tests (empty=none).
* `tests` `path`: The path where your tests are.

## Test definition
This could be a test with a couple of rules:
```php
<?php

use PhpAT\Rule\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class ExampleTest extends ArchitectureTest
{
    public function testDomainDoesNotDependOnOtherLayers(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('Domain/*'))
            ->shouldNotDependOn()
            ->classesThat(Selector::havePathname('Application/*'))
            ->andClassesThat(Selector::havePathname('Infrastructure/*'))
            ->andClassesThat(Selector::havePathname('Presentation/*'))
            ->excludingClassesThat(Selector::havePathname('Application/Shared/Service/KnownBadApproach.php'))
            ->build();
    }
    
    public function testAllHandlersExtendAbstractCommandHandler(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('Application/*/UseCase/*Handler.php'))
            ->excludingClassesThat(Selector::havePathname('Application/Shared/UseCase/Different*Handler.php'))
            ->andExcludingClassesThat(Selector::havePathname('Application/Shared/UseCase/AbstractCommandHandler.php'))
            ->shouldExtend()
            ->classesThat(Selector::havePathname('Application/Shared/UseCase/AbstractCommandHandler.php'))
            ->build();
    }
}
```

## Usage
```bash
vendor/bin/phpat phpat.yml
```

# Contributing
**PHP Architecture Tester** is in a very early stage, contributions are welcome. Please take a look to the [Contribution docs](.github/CONTRIBUTING.md).
