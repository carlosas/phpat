# Assertions

Assertion is the type of verification that can be done in the selected classes.

## BeNamed

`should->beNamed()`: asserts that the selected classes are **named** as the namespaced name specified.

## BeFinal

`should->beFinal()`: asserts that the selected classes are declared as **final**.

`shouldNot->beFinal()`: asserts that the selected classes are **not declared as final**.

## BeEnum

`should->beEnum()`: asserts that the selected classes are **enums**.

`shouldNot->beEnum()`: asserts that the selected classes are **not enums**.

## BeAbstract

`should->beAbstract()`: asserts that the selected classes are declared as **abstract**.

`shouldNot->beAbstract()`: asserts that the selected classes are **not declared as abstract**.

## BeInterface

`should->beInterface()`: asserts that the selected classes are **interfaces**.

## BeReadonly

`should->beReadonly()`: asserts that the selected classes are declared as **readonly**.

`shouldNot->beReadonly()`: asserts that the selected classes are **not declared as readonly**.

## BeInvokable

`should->beInvokable()`: asserts that the selected classes are **invokable** by implementing `__invoke` method.

`shouldNot->beInvokable()`: asserts that the selected classes are **not invokable** by implementing `__invoke` method.

## HaveOnlyOnePublicMethod

`should->haveOnlyOnePublicMethod()`: asserts that the selected classes **only have **one public method\*\* (besides constructor).

## HaveOnlyOnePublicMethodNamed

`should->haveOnlyOnePublicMethodNamed()`: asserts that the selected classes **only have one public method with specified name** (besides constructor).

## Extend

`should->extend()`: asserts that the selected classes **extend** the target class.

`shouldNot->extend()`: asserts that the selected classes **do not extend** the target class.

## Implement

`should->implement()`: asserts that the selected classes **implement** the target interfaces.

`shouldNot->implement()`: asserts that the selected classes **do not implement** the target interfaces.

## Include

`should->include()`: asserts that the selected classes **include** the target traits.

`shouldNot->include()`: asserts that the selected classes **do not include** the target traits.

## DependOn

`should->dependOn()`: asserts that the selected classes **depend** on the target classes.

`shouldNot->dependOn()`: asserts that the selected classes **do not depend** on the target classes.

`canOnly->dependOn()`: asserts that the selected classes **do not depend** on anything else than the target classes.

## Construct

`should->construct()`: asserts that the selected classes **use the constructor** of the target classes.

`shouldNot->construct()`: asserts that the selected classes **do not use the constructor** of the target classes.

## Exist

`should->exist()`: asserts that the selected classes **exist**.

`shouldNot->exist()`: asserts that the selected classes **do not exist**.

## ApplyAttribute

`should->applyAttribute()`: asserts that the selected classes **apply** the target attributes.
