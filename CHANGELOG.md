# Changelog
All notable changes to this project will be documented in this file.

## 0.5.2
- Added `Selector::implementInterface` to select classes that implement a certain interface
- Added `Selector::extendClass` to select classes that extend a certain class
- Added `Selector::includeTrait` to select classes that include a certain trait

## 0.5.1
- Added `Selector::haveClassName` to select classes by fully qualified names
- Performance boost by building an AST map instead of parsing each time

## 0.5.0
- Added a changelog :smile:
- Changed Minimum PHP version from `7.1` to `7.2`.
- Changed dependency `symfony/event-dispatcher` to `carlosas/simple-event-dispatcher`.
