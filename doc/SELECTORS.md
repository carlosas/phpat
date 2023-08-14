# Selectors

###### Selectors are the way to tell PHPat which classes are going to intervene in a rule.

You can always use a regular expression to select everything that matches that expression.

---

### `Selector::AND()`
Select classes that match all the inner Selectors.

### `Selector::NOT()`
Select classes that do not match the inner Selector.

### `Selector::all()`
Select all classes.

### `Selector::classname()`
Select classes with the given fully qualified name.

### `Selector::namespace()`
Select classes in the given namespace.

### `Selector::implements()`
Select classes that implement a given interface.

### `Selector::extends()`
Select classes that extend a given class.

### `Selector::interface()`
Select all interfaces.

### `Selector::enum()`
Select all enums.

### `Selector::abstract()`
Select all abstract classes.

### `Selector::final()`
Select all final classes.

### `Selector::readonly()`
Select all readonly classes.

### `Selector::attribute()`
Select all attribute classes.
