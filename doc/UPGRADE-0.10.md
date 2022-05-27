UPGRADE to 0.10.0
=================

The tool has been converted to a PHPStan extension. Now it requires the user to
run PHPStan on the project with the PHPat extension activated.

Configuration
-------------

* Remove your `phpat.yaml`, use `phpat.neon` instead.
* Instead of a test suite path, use fully qualified names of your test classes. 
* Enable the extension in your PHPStan configuration.

```neon
# phpstan.neon
includes:
    - vendor/phpat/phpat/extension.neon
    - phpat.neon
```
```neon
# phpat.neon
parameters:
    phpat:
        tests:
            - Tests\Architecture\MyFirstTest
            - Tests\Architecture\MySecondTest
```

Tests
-----

* Test classes do **not** extend `ArchitectureTest` anymore.
* Instead of ```$this->rule```, the rule builder gets started with ```PHPat::rule()```.
* Instead of using ```andClassesThat()```, all selectors are now passed as consecutive arguments
of a single ```classes()``` method.
* Selectors do not accept `*` as a wildcard anymore. Instead, you can write a regular expression.
```php
PHPat::rule()
    ->classes(Selector::namespace('App\Domain'))
    ->shouldNotDependOn()
    ->classes(
        Selector::namespace('App\Application'),
        Selector::namespace('App\Infrastructure')
    )
    ->build();
```
