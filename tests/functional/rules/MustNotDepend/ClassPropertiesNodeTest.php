<?php declare(strict_types=1);

namespace Tests\PhpAT\functional\rules\MustNotDepend;

use PhpAT\Rule\Assertion\Dependency\MustNotDepend\MethodParamRule;
use PhpAT\Rule\Assertion\Dependency\MustNotDepend\MustNotDepend;
use PhpAT\Rule\Assertion\Dependency\MustNotDepend\New_Rule_;
use PhpAT\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Tests\PhpAT\functional\FakeTestParser;
use Tests\PhpAT\functional\fixtures\Dependency\Constructor;
use Tests\PhpAT\functional\fixtures\Dependency\DependencyNamespaceSimpleClass;

/**
 * @extends RuleTestCase<MethodParamRule>
 */
class ClassPropertiesNodeTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        $assertion = MustNotDepend::class;
        $subjects = [Constructor::class];
        $targets = [DependencyNamespaceSimpleClass::class];

        return new MethodParamRule(new StatementBuilderFactory(new FakeTestParser($assertion, $subjects, $targets)));
    }

    public function testRule(): void
    {
        $this->analyse(['tests/functional/fixtures/Dependency/Constructor.php'], [
            [
                sprintf(
                    '%s must not depend on %s%s%s%s',
                    Constructor::class,
                    DependencyNamespaceSimpleClass::class,
                    PHP_EOL,
                    '    💡 ',
                    MethodParamRule::class
                ),
                14,
            ],
        ]);
    }
}
