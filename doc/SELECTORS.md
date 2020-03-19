# Selectors

Selectors are the way to tell PHPAT which classes are going to intervene in a rule.

You can always use any amount of `*` in the middle of any selector parameter to select everything that match that expression.

### `ClassNameSelector`
With `Selector::haveClassName()` you can select classes using their Fully Qualified Class Name.

### `PathSelector`
With `Selector::havePath()` you can select classes in those PHP files.

### `ImplementSelector`
With `Selector::implementInterface()` you can select classes that implement a certain interface.

### `ExtendSelector`
With `Selector::extendClass()` you can select classes that extend a certain class.

### `IncludeSelector`
With `Selector::includeTrait()` you can select classes that include a certain trait.

### `ComposerSourceSelector`
With `Selector::areAutoloadableFromComposer()` you can select classes that are declared as sources in the given `composer.json` file

### `ComposerDependencySelector`
With `Selector::areDependenciesFromComposer()` you can select classes that are declared composer dependencies of the given `composer.json` and `composer.lock` combination.
