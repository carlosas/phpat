<?php declare(strict_types=1);

namespace Tests\PhpAT\functional\rules\MustNotDepend;

use PhpAT\Rule\Assertion\Dependency\MustNotDepend\MustNotDepend;
use PhpAT\Rule\Assertion\Dependency\MustNotDepend\New_Rule;
use PhpAT\Rule\Assertion\Dependency\MustNotDepend\New_Rule_;
use PhpAT\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Tests\PhpAT\functional\FakeTestParser;
use Tests\PhpAT\functional\fixtures\Dependency\Constructor;

/**
 * @extends RuleTestCase<New_Rule>
 */
class New_Test extends RuleTestCase
{
    protected function getRule(): Rule
    {
        $assertion = MustNotDepend::class;
        $subjects = [Constructor::class];
        $targets = [\BadMethodCallException::class];

        return new New_Rule(new StatementBuilderFactory(new FakeTestParser($assertion, $subjects, $targets)));
    }

    public function testRule(): void
    {
        $this->analyse(['tests/functional/fixtures/Dependency/Constructor.php'], [
            [
                sprintf(
                    '%s must not depend on %s%s%s%s',
                    Constructor::class,
                    \BadMethodCallException::class,
                    PHP_EOL,
                    '    💡 ',
                    New_Rule::class
                ),
                17,
            ],
        ]);
    }
}
