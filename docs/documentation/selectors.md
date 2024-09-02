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

## Selector::isError()
Select classes that extend the `\Error` class.

## Selector::isException()
Select classes that extend the `\Exception` class.

## Selector::isThrowable()
Select classes that implement the `\Throwable` interface.

## Selector::implements()
Select classes that implement the given interface.

## Selector::extends()
Select classes that extend the given class.

## Selector::isInterface()
Select all interfaces.

## Selector::appliesAttribute()
Select classes that applies the given attribute.

## Selector::isAbstract()
Select all abstract classes.

## Selector::isAttribute()
Select all attribute classes.

## Selector::isEnum()
Select all enums.

## Selector::isFinal()
Select all final classes.

## Selector::isReadonly()
Select all readonly classes.

## Selector::isTrait()
Select all traits.

<br />

---

## Selector::AND()
Selects classes that match all the inner Selectors.

Example:

```php
Selector::AND(
    Selector::inNamespace('App\User'),
    Selector::isAbstract()
)
```

This will select all abstract classes in the `App\User` namespace.

## Selector::NOT()
Selects classes that do not match the inner Selector.

```php
Selector::NOT(
    Selector::inNamespace('App\User')
)
```

This will select all classes that are not in the `App\User` namespace.

## Selector::AllOf(...$selectors)

Selects classes that match all the inner Selectors. (and operator)

```php
Selector::AllOf(
    Selector::namespace('App\User'),
    Selector::isAbstract()
)
```

This will select all abstract classes in the `App\User` namespace.

## Selector::AnyOf(...$selectors)

Selects classes that match any of the inner Selectors. (or operator)

```php
Selector::AnyOf(
    Selector::namespace('App\User'),
    Selector::isAbstract()
)
```

This will select all classes in the `App\User` namespace and all abstract classes.

## Selector::NoneOf(...$selectors)

Selects classes that do not match any of the inner Selectors. (not of operator)

```php
Selector::NoneOf(
    Selector::namespace('App\User'),
    Selector::isAbstract()
)
```

This will select all classes that are not in the `App\User` namespace and are not abstract.

## Selector::OneOf(...$selectors)

Selects classes that match exactly one of the inner Selectors. (xor operator)

```php
Selector::OneOf(
    Selector::namespace('App\User'),
    Selector::isAbstract(),
    Selector::isTrait()
)
```

This will select all classes that are in the `App\User` namespace or are abstract or are traits, but not more than one of them.


## Selector::AtLeastCountOf(int $min, ...$selectors)

Selects classes that match at least X of the inner Selectors. (at least x of operator)

```php
Selector::AtLeastCountOf(2,
    Selector::namespace('App\User'),
    Selector::isAbstract()
)
```

This will select all classes that are in the `App\User` namespace and are abstract.

## Selector::AtMostCountOf(int $max, ...$selectors)

Selects classes that match at most X of the inner Selectors. (at most x of operator)

```php
Selector::AtMostCountOf(1,
    Selector::namespace('App\User'),
    Selector::isAbstract()
)
```

This will select all classes that are in the `App\User` namespace or are abstract, but not both.
