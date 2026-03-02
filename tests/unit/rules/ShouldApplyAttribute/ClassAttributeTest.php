<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldApplyAttribute;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\ApplyAttribute\ClassAttributeRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassAttributeRule>
 * @internal
 * @coversNothing
 */
class ClassAttributeTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\ShouldApplyAttribute\ClassAttributeTest\Subject';
    private const TARGET = 'Fixture\ShouldApplyAttribute\ClassAttributeTest\Target';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldApplyAttribute\ClassAttributeTest;
            #[\Attribute(\Attribute::TARGET_CLASS)]
            class Target {}
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should apply the attribute %s', self::SUBJECT, self::TARGET), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'applyAttribute',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::TARGET, false)]
        );

        return new ClassAttributeRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
