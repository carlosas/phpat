<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\ApplyAttribute;

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
class ClassAttributeRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\Relation\ApplyAttribute\ShouldConstraint\Subject';
    private const TARGET = 'Fixture\Relation\ApplyAttribute\ShouldConstraint\Target';

    public function testShouldConstraint(): void
    {
        // Class not applying the attribute — should produce an error
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\ApplyAttribute\ShouldConstraint;
            #[\Attribute(\Attribute::TARGET_CLASS)]
            class Target {}
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should apply the attribute %s', self::SUBJECT, self::TARGET), 5],
        ]);

        // Class correctly applying the attribute — no errors
        $subject2 = 'Fixture\Relation\ApplyAttribute\ShouldConstraintPass\Subject';
        $target2 = 'Fixture\Relation\ApplyAttribute\ShouldConstraintPass\Target';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\ApplyAttribute\ShouldConstraintPass;
            #[\Attribute(\Attribute::TARGET_CLASS)]
            class Target {}
            #[Target]
            class Subject {}
            PHP);

        $testParser2 = FakeTestParser::create(
            'test',
            Constraint::Should,
            'applyAttribute',
            [new Classname($subject2, false)],
            [new Classname($target2, false)]
        );

        $rule2 = new ClassAttributeRule(
            new StatementBuilder($testParser2),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );

        $this->analyse([$file2], []);
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
