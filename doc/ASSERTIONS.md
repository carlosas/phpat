# Assertions

Assertion are the type of verification that can be done in the selected classes.

There are four groups of assertions: Dependency, Inheritance, Composition and Mixin.

## Dependency

### `mustDependOn()`
It asserts that the selected classes **depend** on the other selected classes.

### `mustNotDependOn()`
It asserts that the selected classes **do not depend** on the other selected classes.

### `mustOnlyDependOn()`
It asserts that the selected classes **depend only** on the other selected classes **and no others**.

### `canOnlyDependOn()`
It asserts that the selected classes **do not have other depencies** more the selected classes.

## Composition

### `mustImplement()`
It asserts that the selected classes **implement** the selected interfaces.

### `mustNotImplement()`
It asserts that the selected classes **do not implement** the selected interfaces.

### `mustOnlyImplement()`
It asserts that the selected classes **implement only** the selected interfaces **and no others**.

### `canOnlyImplement()`
It asserts that the selected classes **do not implement other interfaces** more than the selected ones.

## Inheritance

### `mustExtend()`
It asserts that the selected classes **extend** the selected class.

### `mustNotExtend()`
It asserts that the selected classes **do not extend** the selected classes.

### `canOnlyExtend()`
It asserts that the selected classes **do not extend other classes** more than the selected ones.

## Mixin

### `mustInclude()`
It asserts that the selected classes **include** the selected traits.

### `mustNotInclude()`
It asserts that the selected classes **do not include** the selected traits.

### `mustOnlyInclude()`
It asserts that the selected classes **include only** the selected traits **and no others**.

### `canOnlyInclude()`
It asserts that the selected classes **do not include other traits** more than the selected ones.
