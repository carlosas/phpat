<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\CanOnlyDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\MethodReturnRule;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\CanOnlyDepend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\fixtures\Simple\SimpleInterface;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<MethodReturnRule>
 */
class MethodReturnTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleInterface::class), 44],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            CanOnlyDepend::class,
            [new Classname(FixtureClass::class, false)],
            [
                new Classname(SimpleClass::class, false),
            ]
        );

        return new MethodReturnRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
