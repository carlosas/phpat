<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\CanOnlyDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\IncludedTraitsRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IncludedTraitsRule>
 * @internal
 * @coversNothing
 */
class IncludedTraitsTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testCanOnlyDependIncludedTraits';
    private const SUBJECT = 'Fixture\CanOnlyDepend\IncludedTraitsTest\Subject';
    private const ALLOWED = 'Fixture\CanOnlyDepend\IncludedTraitsTest\Allowed';
    private const TARGET = 'Fixture\CanOnlyDepend\IncludedTraitsTest\Target';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\CanOnlyDepend\IncludedTraitsTest;
            trait Allowed {}
            trait Target {}
            class Subject
            {
                use Target;
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', self::SUBJECT, self::TARGET), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::CanOnly,
            'depend',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::ALLOWED, false)]
        );

        return new IncludedTraitsRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
