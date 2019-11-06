<p align="center">
    <img width="500px" src="https://raw.githubusercontent.com/carlosas/phpat/master/.github/logo.png" alt="Logo">
</p>
<h2 align="center">Easy to use architecture testing tool for PHP</h2>
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
	<a>
		<img src="http://hits.dwyl.com/carlosas/phpat.svg" alt="Hits">
	</a>
</p>
<br />

### Introduction 📜

**PHP Architecture Tester** is a tool that helps you keep your project architecture clean.

It provides a natural language abstraction to define your own architecture rules and test them against your software.
You can also integrate *phpat* easily into your toolchain.

Current supported relations:

* **Dependency**: *SomeClass* depends (or not) on *AnotherClass*
* **Inheritance**: *SomeClass* extends (or not) *AnotherClass*
* **Composition**: *SomeClass* implements (or not) *SomeInterface*
* **Mixin**: *SomeClass* includes (or not) *SomeTrait*

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
# phpat.yml
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

<h2></h2>

### Test definition 📓

This could be a test with a couple of rules:
```php
<?php

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

class ExampleTest extends ArchitectureTest
{
    public function testDomainDoesNotDependOnOtherLayers(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('Domain/*'))
            ->mustNotDependOn()
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
            ->mustExtend()
            ->classesThat(Selector::havePathname('Application/Shared/UseCase/AbstractCommandHandler.php'))
            ->build();
    }
}
```

<h2></h2>

### Usage 🚀

Run the bin with your configuration file:
```bash
vendor/bin/phpat phpat.yml
```

<h2></h2>

⚠ Launching early stage releases (0.x.x) with a different SemVer strategy. We are using *minor* for breaking changes.
This will change to strict SemVer with the release of `1.0.0`. See [Semantic Versioning](https://semver.org/).

<h2></h2>

**PHP Architecture Tester** is in a very early stage, contributions are welcome. Please take a look to the [Contribution docs](.github/CONTRIBUTING.md).
