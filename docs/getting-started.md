### Installation

Require **PHPat** with [Composer](https://getcomposer.org/):
```bash
composer require --dev phpat/phpat
```

Activate the extension using one of the following methods:

<details>
  <summary>Automatic activation</summary>

```bash
composer require --dev phpstan/extension-installer
```
</details>

<details>
  <summary>Manual activation</summary>

```neon
# phpstan.neon
includes:
    - vendor/phpat/phpat/extension.neon
```
</details>

<h2></h2>

### Configuration

You will need to register your test classes in your PHPStan configuration:
```neon
# phpstan.neon
services:
    -
        class: Tests\Architecture\MyFirstTest
        tags:
            - phpat.test
```
⚠️ Your architecture tests folder should be included in the PHPStan analysed paths.

You can configure some PHPat options as follows:
```neon
# phpstan.neon
parameters:
    phpat:
        ignore_built_in_classes: true
```

See the complete list of options in the [Configuration](documentation/configuration.md) section.
<br />

<h2></h2>

### Test definition

There are different [Selectors](documentation/selectors.md) available to select the classes involved in a rule, and a wide set of [Assertions](documentation/assertions.md).

Here's an example test with a rule:

```php
<?php

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use App\Domain\SuperForbiddenClass;

final class MyFirstTest
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
            )
            ->because('this will break our architecture, implement it another way! see /docs/howto.md');
    }
}
```

<h2></h2>

### Usage

Run **PHPStan** as usual:
```bash
php vendor/bin/phpstan analyse -c phpstan.neon
```
