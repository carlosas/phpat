<p align="center">
    <img width="500px" src="https://raw.githubusercontent.com/carlosas/phpat/master/.github/logo.png" alt="PHP Architecture Tester">
</p>
<h2 align="center">Easy to use architecture testing tool for PHP</h2>
<p align="center">
	<a>
		<img src="https://img.shields.io/packagist/v/carlosas/phpat?label=version&style=for-the-badge" alt="Version">
    </a>
	<a>
		<img src="https://img.shields.io/packagist/php-v/carlosas/phpat?style=for-the-badge" alt="PHP Version">
	</a>
	<a>
		<img src="https://img.shields.io/badge/contributions-welcome-green.svg?style=for-the-badge" alt="Contributions welcome">
	</a>
</p>
<br />

### Introduction 📜

**PHP Architecture Tester** is a static analysis tool to verify architectural requirements.

It provides a natural language abstraction to define your own architectural rules and test them against your software.
You can also integrate *phpat* easily into your toolchain.

There are four groups of supported assertions: **Dependency**, **Inheritance**, **Composition** and **Mixin**.

ℹ️ Check out the section [WHAT TO TEST](doc/WHAT_TO_TEST.md) to see some examples of typical use cases.

<h2></h2>

### Installation 💽

Just require **phpat** with [Composer](https://getcomposer.org/):
```bash
composer require --dev carlosas/phpat
```

<details><summary>Manual download</summary>
<br />

If you have dependency conflicts, you can also download the latest PHAR file from [Releases](https://github.com/carlosas/phpat/releases). 

You will have to use it executing `php phpat.phar phpat.yaml` and declare your tests in XML or YAML.

</details>

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

<details><summary>Complete list of options</summary>
<br />

| Name                                      | Description                                              | Default      |
|-------------------------------------------|----------------------------------------------------------|:------------:|
| `src` `path`                              | The root path of your application                        | *no default* |
|` src` `include`                           | Files you want to be tested excluding the rest           | *all files*  |
| `src` `exclude`                           | Files you want to be excluded in the tests               | *no files*   |
| `tests` `path`                            | The path where your tests are                            | *no default* |
| `options` `verbosity`                     | Output verbosity level (0/1/2)                           | 1            |
| `options` `dry-run`                       | Report failed suite without error exit code (true/false) | false        |
| `options` `ignore_docblocks`              | Ignore relations on docblocks (true/false)               | false        |

</details>

<h2></h2>

### Test definition 📓

There are different [Selectors](doc/SELECTORS.md) to choose which classes will intervene in a rule and a wide range of [Assertions](doc/ASSERTIONS.md).

This could be a test with a couple of rules:
```php
<?php

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use App\Domain\BlackMagicInterface;

class ExampleTest extends ArchitectureTest
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

<details><summary>YAML / XML test definition</summary>
<br />

You can also define tests whether in YAML or XML.

```yaml
rules:
  testAssertionsImplementAssertionInterface:
    - classes:
        - havePath: Rule/Assertion/*
    - excluding:
        - haveClassName: PhpAT\Rule\Assertion\*\MustNot*
        - havePath: Rule/Assertion/MatchResult.php
    - assert: mustExtend
    - classes:
        - haveClassName: PhpAT\Rule\Assertion\AbstractAssertion
```
```xml
<?xml version="1.0" encoding="UTF-8" ?>
<test xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
       xsi:schemaLocation="https://raw.githubusercontent.com/carlosas/phpat/master/src/Test/Test.xsd">
    <rule name="testAssertionsDoNotDependOnVendors">
        <classes>
            <selector type="havePath">Rule/Assertion/*</selector>
        </classes>
        <assert>canOnlyDependOn</assert>
        <classes>
            <selector type="haveClassName">PhpAT\*</selector>
            <selector type="haveClassName">Psr\*</selector>
        </classes>
    </rule>
</test>
```

</details>

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

**PHP Architecture Tester** is in a very early stage, contributions are welcome. Please have a look to the [Contribution docs](.github/CONTRIBUTING.md).
