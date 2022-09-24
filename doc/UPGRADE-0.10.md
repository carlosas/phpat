UPGRADE to 0.10.0
=================

###### The tool has been converted to a PHPStan extension. Now it requires the user to run it with the PHPat extension activated.

ℹ️ Some features have been removed, but will come back in future versions.

Configuration
-------------

* Remove your `phpat.yaml`, use `phpat.neon` instead.
* Instead of a test suite path, use fully qualified names of your test classes. 
* Enable the extension in your PHPStan configuration.
* The configuration `ignore-docblocks` is now called `ignore_doc_comments`.
* The rest of configurations do not exist anymore.

```neon
# phpstan.neon
includes:
    - vendor/phpat/phpat/extension.neon
    - phpat.neon
```
```neon
# phpat.neon
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
⚠️ Your architecture tests folder should be included in the PHPStan analysed paths.

Tests
-----

* Test classes do **not** extend `ArchitectureTest` anymore.
* Instead of ```$this->rule```, the rule builder gets started with ```PHPat::rule()```.
* Instead of using ```andClassesThat()```, all selectors are now passed as consecutive arguments
of a single ```classes()``` method.
* The build process does not need to end with a `build()` anymore.
```php
PHPat::rule()
    ->classes(Selector::namespace('App\Domain'))
    ->excluding(Selector::classname(KnownBadApproach::class))
    ->shouldNotDependOn()
    ->classes(
        Selector::namespace('App\Application'),
        Selector::namespace('App\Infrastructure')
    );
```

You can use regular expressions in selectors, but be aware that you might need to escape the backlashes properly.
Example:
```php
Selector::namespace('/^App\\\\.*\\\\Application\\\\.*/', true),
```

Selectors
---------

* Arguments do not accept the `*` wildcard anymore. Instead, you can now use a regular expression setting the second parameter to true.
* Composer selectors are not available anymore, at least not for now.
* Selector methods have changed slightly and some new have been added. Check the [Selectors docs](SELECTORS.md) for more information.

Assertions
----------

* The verb `must` has been replaced by `should`.
* Assertions have changed slightly as well, this is the list of current assertions:
  * should extend
  * should implement
  * should not extend
  * should not implement
  * should not depend on
  * should not construct
* See the [Assertions docs](ASSERTIONS.md) for more information.
