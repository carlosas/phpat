<p align="center">
    <img width="500px" src="https://raw.githubusercontent.com/carlosas/phpat/master/.github/logo.png" alt="PHP Architecture Tester">
</p>
<h2 align="center">Easy to use architecture testing tool for PHP</h2>
<p align="center">
	<a>
		<img src="https://img.shields.io/packagist/v/carlosas/phpat.svg?style=flat-square" alt="Packagist">
    </a>
	<a>
		<img src="https://img.shields.io/badge/php-%3E%3D_7.2-8892BF.svg?style=flat-square" alt="Minimum PHP">
	</a>
	<a>
		<img src="https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square" alt="MIT license">
	</a>
	<a>
		<img src="https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat-square" alt="Contributions welcome">
	</a>
	<a>
		<img src="http://hits.dwyl.com/carlosas/phpat.svg" alt="Hits">
	</a>
</p>
<br />

### Introduction 📜

**PHP Architecture Tester** is a static analysis tool to verify architectural requirements.

It provides a natural language abstraction to define your own architectural rules and test them against your software.
You can also integrate *phpat* easily into your toolchain.

There are four groups of supported assertions: **Dependency**, **Inheritance**, **Composition** and **Mixin**.
<h2></h2>

### Installation 💽

Just require **phpat** with [Composer](https://getcomposer.org/):
```bash
composer require --dev carlosas/phpat
```

<h2></h2>

### Configuration 🔧

You might want to setup a basic configuration:
```yaml
# phpat.yaml
src:
  path: src/
tests:
  path: tests/architecture/
```
This is the complete list of options:
* `src` `path`: The root path of your application.
* `src` `include`: Files you want to be tested excluding the rest (default=all).
* `src` `exclude`: Files you want to be excluded in the tests (default=none).
* `tests` `path`: The path where your tests are.
* `options` `verbosity`: 0/1/2 output verbosity level (default=1).
* `options` `dry-run`: true/false report failed suite without error exit code (default=false).
* `options` `dependency` `ignore_docblocks`: true/false ignore dependencies on docblocks (default=false).

<h2></h2>

### Test definition 📓

There are different ways to choose which classes will intervene in a rule (called Selectors) and many different possibles Assertions.
Check the complete list of both here:

* [Selectors](doc/SELECTORS.md)
* [Assertions](doc/ASSERTIONS.md)

This could be a test with a couple of rules:
```php
<?php

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use App\Domain\BlackMagicInterface;

final class ExampleTest extends ArchitectureTest
{
    public function testDomainDoesNotDependOnOtherLayers(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName('App\Domain\*'))
            ->excludingClassesThat(Selector::implementInterface(BlackMagicInterface::class))
            ->canOnlyDependOn()
            ->classesThat(Selector::havePath('Domain/*'))
            ->andClassesThat(Selector::haveClassName('App\Application\Shared\Service\KnownBadApproach'))
            ->build();
    }

    public function testAllHandlersExtendAbstractCommandHandler(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Application/*/UseCase/*Handler.php'))
            ->excludingClassesThat(Selector::extendClass('App\Application\Shared\UseCase\DifferentHandler'))
            ->andExcludingClassesThat(Selector::includeTrait('App\Legacy\LegacyTrait'))
            ->andExcludingClassesThat(Selector::haveClassName(\App\Application\Shared\UseCase\AbstractCommandHandler::class))
            ->mustExtend()
            ->classesThat(Selector::haveClassName('App\Application\Shared\UseCase\AbstractCommandHandler'))
            ->build();
    }
}
```

<h2></h2>

### Usage 🚀

Run the bin with your configuration file:
```bash
vendor/bin/phpat phpat.yaml
```

<h2></h2>

⚠ Launching early stage releases (0.x.x) could break the API according to [Semantic Versioning 2.0](https://semver.org/). We are using *minor* for breaking changes.
This will change with the release of the stable `1.0.0` version.

<h2></h2>

**PHP Architecture Tester** is in a very early stage, contributions are welcome. Please take a look to the [Contribution docs](.github/CONTRIBUTING.md).
