<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\Named;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\Named\ClassnameRule;
use PHPat\Selector\Selector;
use PHPat\Statement\StatementBuilder;
use PHPat\Test\PHPat;
use PHPat\Test\RelationRule;
use PHPat\Test\TestParser;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\PHPat\unit\CreatesPhpFile;

/**
 * @extends RuleTestCase<ClassnameRule>
 * @internal
 * @coversNothing
 */
class ClassnameRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private RelationRule $definition;

    /**
     * @param array<string, bool|string> $params
     */
    #[DataProvider('assertionCases')]
    public function testAssertion(Constraint $constraint, string $code, array $params, ?string $message): void
    {
        $namespace = 'Fixture\Declaration\Named\\'.$this->dataName();
        $subject = $namespace.'\Subject';
        $selected = PHPat::rule()->classes(Selector::classname(isset($params['unselected']) ? $subject.'Other' : $subject));
        $step = $constraint === Constraint::Should ? $selected->should() : $selected->shouldNot();
        $name = $params['regex'] ? $params['name'] : $namespace.'\\'.$params['name'];
        $this->definition = ($params['regex'] ? $step->beNamed(classname: $name, regex: true) : $step->beNamed(classname: $name))();
        self::assertSame(['isRegex' => $params['regex'], 'classname' => $name], $this->definition->getParams());
        $this->definition->ruleName = 'test';
        self::assertSame($constraint, $this->definition->getConstraint());
        self::assertSame('beNamed', $this->definition->getAssertionType());
        $file = $this->createPhpFile("<?php\nnamespace ".$namespace.";\n".$code);

        $this->analyse([$file], $message === null ? [] : [[sprintf($message, $subject, $namespace), 3]]);
    }

    /**
     * @return array<string, array{Constraint, string, array<string, bool|string>, ?string}>
     */
    public static function assertionCases(): array
    {
        return [
            'ShouldUnselected' => [Constraint::Should, 'class Subject {}', ['name' => 'Other', 'regex' => false, 'unselected' => true], null],
            'ShouldNotUnselected' => [Constraint::ShouldNot, 'class Subject {}', ['name' => 'Subject', 'regex' => false, 'unselected' => true], null],
            'ShouldExactMismatch' => [Constraint::Should, 'class Subject {}', ['name' => 'Other', 'regex' => false], '%1$s should be named %2$s\Other'],
            'ShouldExactMatch' => [Constraint::Should, 'class Subject {}', ['name' => 'Subject', 'regex' => false], null],
            'ShouldRegexMismatch' => [Constraint::Should, 'class Subject {}', ['name' => '/Other$/', 'regex' => true], '%1$s should be named matching the regex /Other$/'],
            'ShouldRegexMatch' => [Constraint::Should, 'class Subject {}', ['name' => '/Subject$/', 'regex' => true], null],
            'ShouldNotExactMismatch' => [Constraint::ShouldNot, 'class Subject {}', ['name' => 'Other', 'regex' => false], null],
            'ShouldNotExactMatch' => [Constraint::ShouldNot, 'class Subject {}', ['name' => 'Subject', 'regex' => false], '%1$s should not be named %2$s\Subject'],
            'ShouldNotRegexMismatch' => [Constraint::ShouldNot, 'class Subject {}', ['name' => '/Other$/', 'regex' => true], null],
            'ShouldNotRegexMatch' => [Constraint::ShouldNot, 'class Subject {}', ['name' => '/Subject$/', 'regex' => true], '%1$s should not be named matching the regex /Subject$/'],
        ];
    }

    protected function getRule(): Rule
    {
        $parser = $this->createMock(TestParser::class);
        $parser->method('__invoke')->willReturn([$this->definition]);

        return new ClassnameRule(
            new StatementBuilder($parser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
