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

## Selector::implements()
Select classes that implement the given interface.

## Selector::extends()
Select classes that extend the given class.

## Selector::isInterface()
Select all interfaces.

## Selector::appliesAttribute()
Select classes that applies the given attribute.

## Selector::isEnum()
Select all enums.

## Selector::isAbstract()
Select all abstract classes.

## Selector::isFinal()
Select all final classes.

## Selector::isReadonly()
Select all readonly classes.

## Selector::isAttribute()
Select all attribute classes.

<br />

---

## Selector::AND()
Selects classes that match all the inner Selectors.

Example:

```php
Selector::AND(
    Selector::namespace('App\User'),
    Selector::abstract()
)
```

This will select all abstract classes in the `App\User` namespace.

## Selector::NOT()
Selects classes that do not match the inner Selector.

```php
Selector::NOT(
    Selector::namespace('App\User')
)
```

This will select all classes that are not in the `App\User` namespace.
