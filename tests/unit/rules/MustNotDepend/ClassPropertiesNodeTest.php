<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\MustNotDepend;

use PHPat\Rule\Assertion\ShouldNotDepend\MethodParamRule;
use PHPat\Rule\Assertion\ShouldNotDepend\ShouldNotDepend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Tests\PHPat\unit\FakeFileTypeMapper;
use Tests\PHPat\unit\FakeReflectionProvider;
use Tests\PHPat\unit\FakeTestParser;
use Tests\PHPat\unit\fixtures\Dependency\Constructor;
use Tests\PHPat\unit\fixtures\Dependency\DependencyNamespaceSimpleClass;

/**
 * @extends RuleTestCase<MethodParamRule>
 */
class ClassPropertiesNodeTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/functional/fixtures/Dependency/Constructor.php'], [
            [
                sprintf(
                    '%s should not depend on %s',
                    Constructor::class,
                    DependencyNamespaceSimpleClass::class,
                ),
                17,
            ],
        ]);
    }

    protected function getRule(): Rule
    {
        $assertion = ShouldNotDepend::class;
        $subjects  = [new Classname(Constructor::class)];
        $targets   = [new Classname(DependencyNamespaceSimpleClass::class)];

        return new MethodParamRule(
            new StatementBuilderFactory(FakeTestParser::create($assertion, $subjects, $targets)),
            new FakeReflectionProvider(),
            FakeFileTypeMapper::create()
        );
    }
}
