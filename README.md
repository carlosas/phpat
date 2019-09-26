<p align="center">
    <img width="500px" src="https://raw.githubusercontent.com/carlosas/phpat/master/.github/logo.png" alt="Logo">
</p>
<h1 align="center">PHP Architecture Tester</h1>
<p align="center">Easy to use architecture testing tool for PHP</p>
<p align="center">
	<a>
		<img src="https://img.shields.io/packagist/v/carlosas/phpat.svg?style=flat-square" alt="Packagist">
    </a>
	<a>
		<img src="https://img.shields.io/badge/php-%3E%3D_7.1-8892BF.svg?style=flat-square" alt="Minimum PHP">
	</a>
	<a>
		<img src="https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square" alt="MIT license">
	</a>
	<a>
		<img src="https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat-square" alt="Contributions welcome">
	</a>
	<a href="https://github.com/carlosas/phpat/issues">
        <img src="https://img.shields.io/github/issues/carlosas/phpat.svg?style=flat-square" alt="Open issues">
	</a>
	<a>
		<img src="http://hits.dwyl.com/carlosas/phpat.svg" alt="Hits">
	</a>
</p>

---

## 📜 Introduction

**PHP Architecture Tester** is a tool that helps you keep your project architecture clean.

It provides a natural language abstraction to define your own architecture rules and test them against your software.
You can also integrate *phpat* easily into your toolchain.

## 💽 Installation

```bash
composer require --dev carlosas/phpat
```

## 🔧 Configuration

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

## 📓 Test definition

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

## 🚀 Usage

```bash
vendor/bin/phpat phpat.yml
```

---

**PHP Architecture Tester** is in a very early stage, contributions are welcome. Please take a look to the [Contribution docs](.github/CONTRIBUTING.md).
