<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\OnePublicMethodNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\OnePublicMethodNamed\HasOnlyOnePublicMethodNamedRule;
use PHPat\Selector\ClassNamespace;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyOnePublicMethodNamedRule>
 * @internal
 * @coversNothing
 */
class HasOnlyOnePublicMethodNamedRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    // All subjects live under this namespace prefix so getRule()'s ClassNamespace selector covers them all
    private const SUBJECT_NS = 'Fixture\Declaration\OnePublicMethodNamed';

    private string $methodName = 'methodWithName';

    private bool $isRegex = false;

    public function testRule(): void
    {
        // Class with multiple public methods matching the expected name — error expected
        $subject1 = self::SUBJECT_NS.'\ClassWithMultiplePublicMethodsTest\Subject';
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\OnePublicMethodNamed\ClassWithMultiplePublicMethodsTest;
            class Subject
            {
                public function methodWithName(): void {}
                public function anotherMethodWithName(): void {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named %s', $subject1, 'methodWithName'), 3],
        ]);

        // Class with no matching public methods — error expected
        $subject2 = self::SUBJECT_NS.'\ClassWithMultiplePublicMethodsNoneMatchingTest\Subject';
        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\OnePublicMethodNamed\ClassWithMultiplePublicMethodsNoneMatchingTest;
            class Subject
            {
                public function unrelated(): void {}
                public function alsoUnrelated(): void {}
            }
            PHP);

        $this->analyse([$file2], [
            [sprintf('%s should have only one public method named %s', $subject2, 'methodWithName'), 3],
        ]);

        // Class with no public methods — error expected
        $subject3 = self::SUBJECT_NS.'\ClassWithNoPublicMethodsTest\Subject';
        $file3 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\OnePublicMethodNamed\ClassWithNoPublicMethodsTest;
            class Subject
            {
                private function secret(): void {}
            }
            PHP);

        $this->analyse([$file3], [
            [sprintf('%s should have only one public method named %s', $subject3, 'methodWithName'), 3],
        ]);

        // Class with exactly one matching public method (and constructor) — no errors
        $file4 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\OnePublicMethodNamed\GoodImplementationTest;
            class Subject
            {
                public function __construct() {}
                public function methodWithName(): void {}
            }
            PHP);

        $this->analyse([$file4], []);
    }

    public function testRuleWithRegexMethod(): void
    {
        $this->methodName = '/^method[a-zA-Z0-9]+/';
        $this->isRegex = true;

        // Class with one method matching regex — no errors
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\OnePublicMethodNamed\GoodImplementationRegexTest;
            class Subject
            {
                public function __construct() {}
                public function methodWithName(): void {}
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRuleWithMoreThanOneRegexMatch(): void
    {
        $this->methodName = '/^ba[a-zA-Z0-9]+/';
        $this->isRegex = true;

        // Class with more than one method matching regex — error expected
        $subject = self::SUBJECT_NS.'\MoreThanOnePublicMethodNamedWithRegexTest\Subject';
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\OnePublicMethodNamed\MoreThanOnePublicMethodNamedWithRegexTest;
            class Subject
            {
                public function bar(): void {}
                public function foo(): void {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named %s', $subject, '/^ba[a-zA-Z0-9]+/'), 3],
        ]);
    }

    public function testRuleWithSimilarlyNamedRegexMethods(): void
    {
        $this->methodName = '/^example[a-zA-Z0-9]+/';
        $this->isRegex = true;

        // Class with two similarly named methods all matching regex — error expected
        $subject = self::SUBJECT_NS.'\SimilarlyNamedPublicMethodsNamedWithRegexTest\Subject';
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\OnePublicMethodNamed\SimilarlyNamedPublicMethodsNamedWithRegexTest;
            class Subject
            {
                public function exampleOne(): void {}
                public function exampleTwo(): void {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named %s', $subject, '/^example[a-zA-Z0-9]+/'), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new ClassNamespace(self::SUBJECT_NS, false)],
            [],
            [],
            ['name' => $this->methodName, 'isRegex' => $this->isRegex]
        );

        return new HasOnlyOnePublicMethodNamedRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            self::createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
