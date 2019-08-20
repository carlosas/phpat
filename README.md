**WARNING:** BIG REFACTOR IN PROGRESS! [Link](https://github.com/carlosas/phparchitest/tree/brutal-refactor)

---

[![Latest Version](https://img.shields.io/packagist/v/carlosas/phparchitest.svg?style=flat-square)](https://packagist.org/packages/carlosas/phparchitest)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D_7.1-8892BF.svg?style=flat-square)](https://github.com/carlosas/phparchitest)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?style=flat-square&color=brightgreen)](LICENSE)
[![contributions](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat-square)](https://github.com/carlosas/phparchitest/issues)
![HitCount](http://hits.dwyl.com/carlosas/php-architest.svg)

PHPArchiTest is an architecture testing tool for PHP.

# Installation
```bash
composer require --dev carlosas/phparchitest
```

# Configuration
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
* `files` `origin_included`: Files you want to be included as origin (empty=all).
* `files` `destination_included`: Files you want to be included as destination (empty=all).
* `files` `origin_excluded`: Files you want to be excluded as origin (empty=none).
* `files` `destination_excluded`: Files you want to be excluded as destination (empty=none).
* `tests` `path`: The path where your tests are.

# Test definition
This could be a test with a couple of rules:
```php
<?php

use PHPArchiTest\Rule\Dependency;
use PHPArchiTest\Rule\Inheritance;
use PHPArchiTest\Rule\Rule;
use PHPArchiTest\Test\ArchiTest;

class ExampleTest extends ArchiTest
{
    public function testDomainDoNotDependOnApplication(): Rule
    {
        return $this->newRule
            ->filesLike('Domain/*')
            ->shouldNotHave(new Dependency())
            ->withFilesLike('Application/*')
            ->excluding('Application/Shared/Service/KnownBadApproach.php')
            ->build();
    }
    
    public function testAllHandlersExtendAbstractCommandHandler(): Rule
    {
        return $this->newRule
            ->filesLike('Application/*/UseCase/*Handler.php')
            ->excluding('Application/Shared/UseCase/TechnicalDebtHandler.php')
            ->excluding('Application/Shared/UseCase/WillBeFixedHandler.php')
            ->shouldHave(new Inheritance())
            ->withFilesLike('Application/Shared/UseCase/AbstractCommandHandler.php')
            ->build();
    }
}
```

# Usage
```bash
vendor/bin/phpat phpat.yml
```
