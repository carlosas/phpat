# Project Guidelines

This page provides guidance when working with code in this repository.

## Overview

PHPat (PHP Architecture Tester) is a **PHPStan extension** that lets users define and enforce architectural rules in PHP codebases. Users define test classes registered in PHPStan config, and PHPat validates those rules during PHPStan analysis.

## Project Structure

```
phpat/
├── ci/                          # CI configuration files (PHPStan, Psalm, PHP-CS-Fixer configs)
├── docs/                        # Project documentation and assets
├── src/
│   ├── Parser/                  # Helpers for parsing types and built-in class lists
│   ├── Rule/
│   │   ├── Assertion/
│   │   │   ├── Declaration/     # Assertion types that check class-level declarations (final, abstract, readonly, etc.)
│   │   │   └── Relation/        # Assertion types that check relationships between classes (depend, extend, implement, etc.)
│   │   └── Extractor/
│   │       ├── Declaration/     # Traits that extract boolean declarations from AST nodes
│   │       └── Relation/        # Traits that extract class names from AST nodes (including DocComment sub-scopes)
│   ├── Selector/                # Selector classes for targeting PHP classes in rules
│   │   └── Modifier/            # Selector combinators
│   ├── Statement/               # Representation of selected classes and parameters to validate an assertion
│   └── Test/
│       ├── Attributes/          # PHP attributes used to annotate test rule methods (e.g., #[TestRule])
│       └── Builder/             # Fluent builder step classes (SubjectStep, AssertionStep, TargetStep, etc.)
└── tests/
    ├── architecture/            # PHPat rules that enforce PHPat's own architecture
    ├── fixtures/                # PHP fixture classes used as subjects/targets in tests
    ├── integration/             # Integration tests for PHPat features
    └── unit/
        ├── rules/               # Unit tests for each assertion type (one directory per assertion)
        ├── selectors/           # Unit tests for selector classes
        └── tips/                # Unit tests for rule tip/reason messages
```

## Commands

```bash
# Fix coding standards
vendor/bin/php-cs-fixer fix --config ./ci/php-cs-fixer.php

# Run PHPStan with PHPat architecture tests (validates src/ + tests/architecture/)
vendor/bin/phpstan analyse -c ci/phpstan-phpat.neon

# Run Psalm static analysis
vendor/bin/psalm -c ci/psalm.xml

# Run tests
vendor/bin/phpunit tests/unit/ tests/integration/

# Run a single test file
vendor/bin/phpunit tests/unit/rules/SomeTest.php
```

## Architecture

### How PHPat Works (End-to-End)

PHPat hooks into PHPStan as a set of registered rules. When PHPStan analyses a file, each PHPat rule (registered in `extension.neon`) fires on matching AST nodes.

1. **Test Discovery**: `TestExtractor` (`src/Test/TestExtractor.php`) reads all services tagged `phpat.test` from PHPStan's DI container. Users register their test classes with this tag in their `phpstan.neon`.

2. **Test Parsing**: `TestParser` (`src/Test/TestParser.php`) reflects on each test class and collects all public methods prefixed with `test` or annotated with `#[TestRule]`. Each method returns a `Rule` builder which is invoked to build the rule.

3. **Statement Building**: `StatementBuilder` (`src/Statement//StatementBuilder.php`) wraps parsed rules and creates the appropriate `Statement` (DTO with Subject, Target, Excludes, Tip, etc.) for each PHPStan rule class.

4. **Assertion Execution**: Each assertion class (e.g., `Depend\MethodParamRule`) is a PHPStan rule that fires on a specific AST node type. It extracts class names from the node, then validates against the statements built from test rules.

### Fluent Builder API

Users build rules using a fluent API starting from `PHPat::rule()`:

```
PHPat::rule()                    # returns SubjectStep
  ->classes(Selector::...)       # returns SubjectExcludeOrConstraintStep
  ->excluding(Selector::...)     # (optional) returns ConstraintStep
  ->shouldNot()                  # returns ShouldStep/ShouldNotStep/CanOnlyStep
  ->dependOn()                   # returns TargetStep (for relation assertions) or TipOrBuildStep (for declaration assertions)
  ->classes(Selector::...)       # returns TargetExcludeOrTipOrBuildStep (for relation assertions)
  ->excluding(Selector::...)     # (optional) returns TipOrBuildStep (for relation assertions)
  ->because('reason')            # (optional) returns Rule
```

Builder steps live in `src/Test/Builder/`. The methods set their rule option and return the next step's class.

### Assertions (Two Categories)

**Relation assertions** (`src/Rule/Assertion/Relation/`) check relationships between classes. For each assertion type (e.g., `Depend`, `Extend`, `Implement`, `Include`), there are many concrete `*Rule` classes — one per AST node type being checked (e.g., `MethodParamRule`, `CatchBlockRule`, `ClassPropertyRule`, `DocParamTagRule`, etc.). Each is registered separately in `extension.neon` as a PHPStan rule.

**Declaration assertions** (`src/Rule/Assertion/Declaration/`) check properties of classes themselves (abstract, final, readonly, interface, etc.). Each assertion type typically has one `*Rule` class.

Adding a New Assertion:

1. Create a directory under `src/Rule/Assertion/Relation/` or `src/Rule/Assertion/Declaration/`
2. Create an abstract class extending `RelationAssertion` or `DeclarationAssertion`
3. Create concrete `*Rule` classes using the appropriate extractor traits
4. Register each concrete rule class in `extension.neon` with tag `phpstan.rules.rule`
5. Add the assertion method to `src/Test/Builder/AssertionStep.php`

### Extractors

Extractors are traits that extract class names (Relation Extractors) or a boolean (Declaration Extractors) from specific AST nodes in order to be used by the Assertions.

Adding a New Extractor:

1. Create a trait class in `src/Rule/Extractor/Declaration/` or `src/Rule/Extractor/Relation/`
2. Implement the `getNodeType()` method, returning a PhpParser or PHPStan node class
3. Implement the extraction method:
   - For Relation Extractors: `extractNodeClassNames()` returning class names found in that node
   - For Declaration Extractors: `meetsDeclaration()` returning true if the node meets the criteria
4. Use the extractor trait in the concrete `*Rule` class (as part of an Assertion)

### Selectors

`src/Selector/SelectorPrimitive.php` defines the base selector factory methods (`classname`, `inNamespace`, `extends`, `implements`, `includes`, `appliesAttribute`, `withFilepath`, flag selectors like `isAbstract`, `isFinal`, etc.).

`src/Selector/Selector.php` extends this with logical combinators: `Not`, `AllOf`, `AnyOf`, `NoneOf`, `AtLeastCountOf`, `AtMostCountOf`, `OneOf`.

Adding a New Selector:

1. Create a class in `src/Selector/` implementing `SelectorInterface`
2. Add the selector method to `src/Selector/SelectorPrimitive.php` for normal selectors (`inNamespace`, `extends`, `implements`, `includes`, `isAbstract`, `isFinal`, etc.) or `src/Selector/Selector.php` for combinators (`Not`, `AllOf`, `AnyOf`, `NoneOf`, etc.)

### Configuration

PHPat parameters configured in user's `phpstan.neon` under `parameters.phpat`:

- `ignore_doc_comments` (default: `false`) — skip PHPDoc tag analysis
- `ignore_built_in_classes` (default: `false`) — skip PHP built-in classes as targets
- `show_rule_names` (default: `false`) — prefix error messages with rule name

## Documentation

The user-facing documentation site lives at **https://phpat.dev** and is built from the `docs/` folder using [MkDocs Material](https://squidfunk.github.io/mkdocs-material/). Key pages:

- `docs/documentation/rules.md` — how to define rules, dynamic rule sets
- `docs/documentation/assertions.md` — all available assertions
- `docs/documentation/selectors.md` — all available selectors and combinators
- `docs/documentation/configuration.md` — `phpstan.neon` parameters
- `docs/documentation/other.md` — PHPStan features available to PHPat users
- `docs/examples.md` — typical use cases and example rule definitions
- `docs/contributing.md` — PR guidelines and local docs setup

## Testing

### Unit Tests

- Selectors
  - Folder: `tests/unit/selectors/`
  - One test class per Selector class (e.g., `IsStandardClassTest.php` or `AppliesAttributeTest.php`)
- Rules
  - Folder: `tests/unit/rules/`
  - One test class per Rule class, mirroring the source Assertion hierarchy (e.g., `Declaration/IsAbstract/AbstractRuleTest.php` or `Relation/Depend/NewRuleTest.php`)
- Features
  - Folder: `tests/unit/features/`
  - One test class per Feature (e.g., `ShowRuleNamesTest.php`)

## Architecture Tests

- Folder: `tests/architecture/`
- PHPat rules that test PHPat's own architecture (run via PHPStan)
