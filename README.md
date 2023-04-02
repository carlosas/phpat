<p align="center">
    <img width="500px" src="https://raw.githubusercontent.com/carlosas/phpat/master/.github/logo.png" alt="PHP Architecture Tester">
</p>
<h2 align="center">Easy to use architecture testing tool for PHP</h2>
<p align="center">
	<a>
		<img src="https://img.shields.io/packagist/v/phpat/phpat?label=last%20version&style=for-the-badge" alt="Version">
    </a>
	<a>
		<img src="https://img.shields.io/packagist/php-v/phpat/phpat?style=for-the-badge" alt="PHP Version">
	</a>
	<a>
		<img src="https://img.shields.io/badge/phpstan-%5E1.3-blue?style=for-the-badge" alt="PHPStan Version">
	</a>
	<a>
		<img src="https://img.shields.io/badge/contributions-welcome-green.svg?style=for-the-badge" alt="Contributions welcome">
	</a>
</p>

<hr />

ℹ️ **PHPat** has been transformed into a [PHPStan](https://phpstan.org/) extension. Read the [UPGRADE notes](doc/UPGRADE-0.10.md).
<br />
The standalone version (v0.9) will remain available and will receive critical bugfixes if required.

<h2></h2>

### Introduction 📜

**PHP Architecture Tester** is a static analysis tool designed to verify architectural requirements.

It provides a natural language abstraction that enables you to define your own architectural rules and and assess their compliance in your code.

Check out the section [WHAT TO TEST](doc/WHAT_TO_TEST.md) to see some examples of typical use cases.

<h2></h2>

### Installation 💽

Require **PHPat** with [Composer](https://getcomposer.org/):
```bash
composer require --dev phpat/phpat
```

If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

<details>
  <summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, enable the extension in your PHPStan configuration:
```neon
# phpstan.neon
includes:
    - vendor/phpat/phpat/extension.neon
```
</details>

<h2></h2>

### Configuration 🔧

You will need to register your test classes in your PHPStan configuration:
```neon
# phpstan.neon
services:
    -
        class: Tests\Architecture\MyFirstTest
        tags:
            - phpat.test
    -
        class: Tests\Architecture\MySecondTest
        tags:
            - phpat.test
```
⚠️ Your architecture tests folder should be included in the PHPStan analysed paths. See [PHPat's own PHPat configuration](ci/phpstan-phpat.neon) as an example.

You can configure some PHPat options as follows:
```neon
# phpstan.neon
parameters:
    phpat:
        ignore_built_in_classes: true
```

<details><summary>Complete list of options</summary>
<br />

| Name                      | Description                           | Default |
|---------------------------|---------------------------------------|:-------:|
| `ignore_doc_comments`     | Ignore relations on Doc Comments      | *false* |
| `ignore_built_in_classes` | Ignore relations with PHP+ext classes | *false* |
| `show_rule_names`         | Show rule name to assertion message   | *false* |

</details>

<h2></h2>

### Test definition 📓

There are different [Selectors](doc/SELECTORS.md) available to select the classes involved in a rule, and a wide set of [Assertions](doc/ASSERTIONS.md).

Here's an example test with a rule:

```php
<?php

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use App\Domain\SuperForbiddenClass;

class MyFirstTest
{
    public function test_domain_does_not_depend_on_other_layers(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('App\Domain'))
            ->shouldNotDependOn()
            ->classes(
                Selector::namespace('App\Application'),
                Selector::namespace('App\Infrastructure'),
                Selector::classname(SuperForbiddenClass::class),
                Selector::classname('/^SomeVendor\\\.*\\\ForbiddenSubfolder\\\.*/', true)
            );
    }
}
```

<h2></h2>

### Usage 🚀

Run **PHPStan** as usual:
```bash
php vendor/bin/phpstan analyse -c phpstan.neon
```

<h2></h2>

> **Warning**<br />
> The launch of early-stage releases (0.x.x) could break the API according to [Semantic Versioning 2.0](https://semver.org/).
> We are using *minor* for breaking changes until the release of the stable `1.0.0` version.

**PHP Architecture Tester** is open source, contributions are welcome. Please have a look to the [Contribution docs](.github/CONTRIBUTING.md).

<br />

**Sponsors** (free license)

<a href="https://jb.gg/OpenSourceSupport">
    <img src="https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.png" alt="JetBrains Logo" width="100"/>
</a>

<a href="https://www.blackfire.io">
    <img src="https://avatars.githubusercontent.com/u/8961067?s=100" alt="BlackFire Logo" width="100"/>
</a>
