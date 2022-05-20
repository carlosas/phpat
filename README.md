<p style="text-align: center;">
    <img width="500px" src="https://raw.githubusercontent.com/carlosas/phpat/master/.github/logo.png" alt="PHP Architecture Tester">
</p>
<h2 style="text-align: center;">Easy to use architecture testing tool for PHP</h2>
<p style="text-align: center;">
	<a>
		<img src="https://img.shields.io/packagist/v/phpat/phpat?label=version&style=for-the-badge" alt="Version">
    </a>
	<a>
		<img src="https://img.shields.io/packagist/php-v/phpat/phpat?style=for-the-badge" alt="PHP Version">
	</a>
	<a>
		<img src="https://img.shields.io/badge/contributions-welcome-green.svg?style=for-the-badge" alt="Contributions welcome">
	</a>
</p>

ℹ️ With **v0.10**, phpat has been converted into a [PHPStan](https://phpstan.org/) extension.

<hr />

### Introduction 📜

**PHP Architecture Tester** is a static analysis tool to verify architectural requirements.

It provides a natural language abstraction to define your own architectural rules and test them against your software.

There are four groups of supported assertions: **Dependency**, **Inheritance**, **Composition** and **Mixin**.

Check out the section [WHAT TO TEST](doc/WHAT_TO_TEST.md) to see some examples of typical use cases.


<h2></h2>

### Installation 💽

Require **phpat** with [Composer](https://getcomposer.org/):
```bash
composer require --dev phpat/phpat
```
Enable the extension in your PHPStan configuration:
```neon
# phpstan.neon
includes:
    - %vendorDir%/phpat/phpat/extension.neon
    - phpat.neon  
```

<h2></h2>

### Configuration 🔧

You will need to setup a minimum configuration:
```neon
# phpat.neon
parameters:
    phpat:
        tests:
            - Tests\Architecture\MyFirstTest
```

<details><summary>Complete list of options</summary>
<br />

| Name                                      | Description                                              | Default      |
|-------------------------------------------|----------------------------------------------------------|:------------:|
| `tests`                                   | List of tests to execute (fully qualified classnames)    | *no default* |

</details>

<h2></h2>

### Test definition 📓

There are different [Selectors](doc/SELECTORS.md) to choose which classes will intervene in a rule and a wide range of [Assertions](doc/ASSERTIONS.md).

This could be a test with a couple of rules:

```php
<?php

use PhpAT\Selector\Selector;
use PhpAT\Test\Rule;
use PhpAT\Test\Phpat;

class MyFirstTest
{
    public function test_domain_does_not_depend_on_other_layers(): Rule
    {
        return Phpat::rule()
            ->classes(Selector::namespace('App\Domain'))
            ->mustNotDependOn()
            ->classes(
                Selector::namespace('App\Application'),
                Selector::namespace('App\Infrastructure')
            )
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

**PHP Architecture Tester** is in a early stage, contributions are welcome. Please have a look to the [Contribution docs](.github/CONTRIBUTING.md).
