# Selectors

###### Selectors are the way to tell PHPat which classes are going to intervene in a rule.

You can always use a regular expression to select everything that matches that expression.

---

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
Select all classes that are abstract.

### `Selector::final()`
Select all classes that are final.
