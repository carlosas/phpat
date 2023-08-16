# Assertions

Assertion is the type of verification that can be done in the selected classes.

## `shouldBeFinal()`
It asserts that the selected classes are declared as **final**.

Also available: `shouldNotBeFinal()`

## `shouldBeAbstract()`
It asserts that the selected classes are declared as **abstract**.

Also available: `shouldNotBeAbstract()`

## `shouldBeReadonly()`
It asserts that the selected classes are declared as **readonly**.

## `shouldExtend()`
It asserts that the selected classes **extend** the target class.

Also available: `shouldNotExtend()`

## `shouldImplement()`
It asserts that the selected classes **implement** the target interfaces.

Also available: `shouldNotImplement()`

## `shouldNotDependOn()`
It asserts that the selected classes **do not depend** on the target classes.

## `shouldNotConstruct()`
It asserts that the selected classes **do not use the constructor** of the target classes.

## `canOnlyDependOn()`
It asserts that the selected classes **does not depend** on anything else than the target classes.

This would be equivalent to `shouldNotDependOn()` with the negation of the target classes.
