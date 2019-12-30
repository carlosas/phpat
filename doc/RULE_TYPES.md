# RuleTypes

RuleType are the type of assertion that can be done on the selected classes.

There are four groups of RuleTypes: Dependency, Inheritance, Composition and Mixin.

## Dependency

### `mustDependOn()`
It asserts that the selected classes **depend** on the other selected classes.

### `mustNotDependOn()`
It asserts that the selected classes **does not depend** on the other selected classes.

### `mustOnlyDependOn()`
It asserts that the selected classes **depend only** on the other selected classes **and no others**.

### `canOnlyDependOn()`
It asserts that the selected classes **does not have other depencies** more the selected classes.

## Composition

### `mustImplement()`
It asserts that the selected classes **implement** the selected interfaces.

### `mustNotImplement()`
It asserts that the selected classes **does not implement** the selected interfaces.

### `mustOnlyImplement()`
It asserts that the selected classes **implement only** the selected interfaces **and no others**.

### `canOnlyImplement()`
It asserts that the selected classes **does not implement other interfaces** more than the selected ones.

## Inheritance

### `mustExtend()`
It asserts that the selected classes **extend** the selected class.

### `mustNotExtend()`
It asserts that the selected classes **does not extend** the selected classes.

### `canOnlyExtend()`
It asserts that the selected classes **does not extend other classes** more than the selected ones.

## Mixin

### `mustInclude()`
It asserts that the selected classes **include** the selected traits.

### `mustNotInclude()`
It asserts that the selected classes **does not include** the selected traits.

### `mustOnlyInclude()`
It asserts that the selected classes **include only** the selected traits **and no others**.

### `canOnlyInclude()`
It asserts that the selected classes **does not include other traits** more than the selected ones.
