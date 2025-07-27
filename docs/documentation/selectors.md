# Selectors

Selectors are the way to tell PHPat which classes are going to intervene in a rule.

You can always use a regular expression, setting the `regex` parameter to *true*, to select everything that matches that expression.

---

## Selector::all()
Selects all classes being parsed.

## Selector::classname()
Selects classes with the given fully qualified name or regex.

```php
Selector::classname('App\User\Domain\UserEntity')
Selector::classname(UserEntity::class)
Selector::classname('/.+Handler/', true)
```

The first two selectors will select the `App\User\Domain\UserEntity` class.

The third one will select all classes whose name ends in `Handler`.

## Selector::inNamespace()
Selects classes in the given namespace.

```php
Selector::inNamespace('App\User\Domain')
Selector::inNamespace('/^App\\\\.+\\\\Domain/', true)
```

The first selector will select all classes in the `App\User\Domain` namespace.

The second one will select all classes in a namespace matching the regular expression.

## Selector::withFilepath()
Selects classes matching the given filepath.

```php
Selector::withFilepath('src/App/User/Domain/UserEntity.php')
Selector::withFilepath('/.+Domain.*\.php/', true)
```

The first selector will select all classes defined in the `src/App/User/Domain/UserEntity.php` file.

The second one will select all classes who have a filepath matching the regular expression.

## Selector::isStandardClass()
Selects Predefined PHP classes (stdClass, ArrayAccess, Exception, Enum...)

## Selector::isError()
Selects classes that extend the `\Error` class.

## Selector::isException()
Selects classes that extend the `\Exception` class.

## Selector::isThrowable()
Selects classes that implement the `\Throwable` interface.

## Selector::implements(string)
Selects classes that implement the given interface.

## Selector::extends(string)
Selects classes that extend the given class.

## Selector::isInterface()
Selects all interfaces.

## Selector::appliesAttribute(string)
Selects classes that applies the given attribute.
You can pass one or more arguments with their values that the attribute should apply to.

## Selector::isAbstract()
Selects all abstract classes.

## Selector::isAttribute()
Selects all attribute classes.

## Selector::isEnum()
Selects all enums.

## Selector::isFinal()
Selects all final classes.

## Selector::isReadonly()
Selects all readonly classes.

## Selector::isTrait()
Selects all traits.

<br />

---

## Selector::NOT(Selector)
Selects classes that do not match the inner Selector.

```php
Selector::NOT(
    Selector::inNamespace('App\User')
)
```

This will select all classes that are not in the `App\User` namespace.

## Selector::AllOf(...Selectors)

Selects classes that match all the inner Selectors. (and operator)

```php
Selector::AllOf(
    Selector::namespace('App\User'),
    Selector::isAbstract()
)
```

This will select all abstract classes in the `App\User` namespace.

## Selector::AnyOf(...Selectors)

Selects classes that match any of the inner Selectors. (or operator)

```php
Selector::AnyOf(
    Selector::namespace('App\User'),
    Selector::isAbstract()
)
```

This will select all classes in the `App\User` namespace and all abstract classes.

## Selector::NoneOf(...Selectors)

Selects classes that do not match any of the inner Selectors. (not of operator)

```php
Selector::NoneOf(
    Selector::namespace('App\User'),
    Selector::isAbstract()
)
```

This will select all classes that are not in the `App\User` namespace and are not abstract.

## Selector::OneOf(...Selectors)

Selects classes that match exactly one of the inner Selectors. (xor operator)

```php
Selector::OneOf(
    Selector::namespace('App\User'),
    Selector::isAbstract(),
    Selector::isTrait()
)
```

This will select all classes that are in the `App\User` namespace or are abstract or are traits, but not more than one of them.


## Selector::AtLeastCountOf(int, ...Selectors)

Selects classes that match at least X of the inner Selectors. (at least x of operator)

```php
Selector::AtLeastCountOf(
    2,
    Selector::namespace('App\User'),
    Selector::isAbstract()
)
```

This will select all classes that are in the `App\User` namespace and are abstract.

## Selector::AtMostCountOf(int, ...Selectors)

Selects classes that match at most X of the inner Selectors. (at most x of operator)

```php
Selector::AtMostCountOf(
    1,
    Selector::namespace('App\User'),
    Selector::isAbstract()
)
```

This will select all classes that are in the `App\User` namespace or are abstract, but not both.
