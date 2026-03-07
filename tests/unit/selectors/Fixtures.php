<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

class DummyClassValid {}
class DummyClassInvalid {}

abstract class AbstractDummyClassValid {}

final class FinalDummyClassValid {}

interface InterfaceDummyClassValid {}

trait TraitDummyClassValid {}

enum EnumDummyClassValid {}

class StandardClassDummyClassValid extends \stdClass {}

#[\Attribute]
class MyAttribute {}

#[MyAttribute]
class AttributeDummyClassValid {}

class HasTraitsDummyClassValid
{
    use TraitDummyClassValid;
}

class ExtendsHasTraitsDummyClassValid extends HasTraitsDummyClassValid {}

class ErrorDummyClassValid extends \Error {}

class ExceptionDummyClassValid extends \Exception {}

class NotHasTraitsDummyClassInvalid {}

class ExtendsDummyClassValid extends DummyClassValid {}

class DoesNotExtendDummyClassInvalid {}

class GrandParentExtendsDummyClassValid extends ExtendsDummyClassValid {}

class ImplementsDummyClassValid implements InterfaceDummyClassValid {}

class DoesNotImplementDummyClassInvalid {}
