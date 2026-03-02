<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\NewRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<NewRule>
 * @internal
 * @coversNothing
 */
class NewRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::ShouldNot;

    private string $subject = 'Fixture\Relation\Depend\New\ShouldNotConstraint\Subject';

    private string $target = 'Fixture\Relation\Depend\New\ShouldNotConstraint\Target';

    private bool $ignoreBuiltInClasses = true;

    public function testShouldNotConstraint(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\New\ShouldNotConstraint;
            class Target {}
            class Subject
            {
                public function create(): Target
                {
                    return new Target();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', $this->subject, $this->target), 8],
        ]);
    }

    public function testShouldNotConstraintDetectsBuiltInClasses(): void
    {
        $this->subject = 'Fixture\Relation\Depend\New\BuiltInTest\Subject';
        $this->target = 'Exception';
        $this->ignoreBuiltInClasses = false;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\New\BuiltInTest;
            class Subject
            {
                public function method(): \Exception
                {
                    return new \Exception();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', $this->subject, $this->target), 7],
        ]);
    }

    public function testCanOnlyConstraint(): void
    {
        $this->constraint = Constraint::CanOnly;
        $this->subject = 'Fixture\Relation\Depend\New\CanOnlyConstraint\Subject';
        $this->target = 'Fixture\Relation\Depend\New\CanOnlyConstraint\Allowed';

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\New\CanOnlyConstraint;
            class Allowed {}
            class Target {}
            class Subject
            {
                public function method(): void
                {
                    $a = new Target();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\New\CanOnlyConstraint\Subject', 'Fixture\Relation\Depend\New\CanOnlyConstraint\Target'), 9],
        ]);

        // Built-in class should not be reported when ignoreBuiltInClasses is true
        $builtInSubject = 'Fixture\Relation\Depend\New\CanOnlyBuiltInTest\Subject';
        $builtInAllowed = 'Fixture\Relation\Depend\New\CanOnlyBuiltInTest\Allowed';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\New\CanOnlyBuiltInTest;
            class Allowed {}
            class Subject
            {
                public function method(): \Exception
                {
                    return new \Exception();
                }
            }
            PHP);

        $testParser2 = FakeTestParser::create(
            'test',
            Constraint::CanOnly,
            'depend',
            [new Classname($builtInSubject, false)],
            [new Classname($builtInAllowed, false)]
        );

        $rule2 = new NewRule(
            new StatementBuilder($testParser2),
            new Configuration(false, false, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );

        $this->analyse([$file2], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'depend',
            [new Classname($this->subject, false)],
            [new Classname($this->target, false)]
        );

        return new NewRule(
            new StatementBuilder($testParser),
            new Configuration(false, $this->ignoreBuiltInClasses, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
