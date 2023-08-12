<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\tips\Relation;

use Attribute;
use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\CanOnlyDepend;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\ClassAttributeRule;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;

use function sprintf;

use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAttribute;

use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassAttributeRule>
 */
class MultipleTipTest extends RuleTestCase
{
    public const RULE_NAME = 'test_FixtureClassCanOnlyDependSimpleAndSpecial';
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [
                sprintf('%s should not depend on %s', FixtureClass::class, SimpleAttribute::class),
                31,
                <<<TIPS
                    • #tip 1
                    • #tip 2
                    TIPS,
            ],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            CanOnlyDepend::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(Attribute::class, false)],
            ['#tip 1', '#tip 2']
        );

        return new ClassAttributeRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
