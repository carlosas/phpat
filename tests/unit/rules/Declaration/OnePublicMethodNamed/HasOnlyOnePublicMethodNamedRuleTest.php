<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\OnePublicMethodNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\OnePublicMethodNamed\HasOnlyOnePublicMethodNamedRule;
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
 * @extends RuleTestCase<HasOnlyOnePublicMethodNamedRule>
 * @internal
 * @coversNothing
 */
class HasOnlyOnePublicMethodNamedRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private RelationRule $definition;

    /**
     * @param array<string, bool|string> $params
     */
    #[DataProvider('assertionCases')]
    public function testAssertion(Constraint $constraint, string $code, array $params, ?string $message): void
    {
        $namespace = 'Fixture\Declaration\OnePublicMethodNamed\\'.$this->dataName();
        $subject = $namespace.'\Subject';
        $selected = PHPat::rule()->classes(Selector::classname($subject));
        $step = $constraint === Constraint::Should ? $selected->should() : $selected->shouldNot();
        $this->definition = ($params['regex'] ? $step->haveOnlyOnePublicMethodNamed(name: $params['name'], isRegex: true) : $step->haveOnlyOnePublicMethodNamed(name: $params['name']))();
        self::assertSame(['name' => $params['name'], 'isRegex' => $params['regex']], $this->definition->getParams());
        $this->definition->ruleName = 'test';
        self::assertSame($constraint, $this->definition->getConstraint());
        self::assertSame('haveOnlyOnePublicMethodNamed', $this->definition->getAssertionType());
        $file = $this->createPhpFile("<?php\nnamespace ".$namespace.";\n".$code);

        $this->analyse([$file], $message === null ? [] : [[sprintf($message, $subject, $namespace), 3]]);
    }

    /**
     * @return array<string, array{Constraint, string, array<string, bool|string>, ?string}>
     */
    public static function assertionCases(): array
    {
        return [
            'ShouldExactZero' => [Constraint::Should, 'class Subject {}', ['name' => 'run', 'regex' => false], '%s should have only one public method named run'],
            'ShouldExactConstructorOnly' => [Constraint::Should, 'class Subject { public function __construct() {} }', ['name' => 'run', 'regex' => false], '%s should have only one public method named run'],
            'ShouldExactOne' => [Constraint::Should, 'class Subject { public function run(): void {} }', ['name' => 'run', 'regex' => false], null],
            'ShouldExactConstructorAndOne' => [Constraint::Should, 'class Subject { public function __construct() {} public function run(): void {} private function helper(): void {} protected function support(): void {} }', ['name' => 'run', 'regex' => false], null],
            'ShouldExactMultiple' => [Constraint::Should, 'class Subject { public function run(): void {} public function other(): void {} }', ['name' => 'run', 'regex' => false], '%s should have only one public method named run'],
            'ShouldExactNonPublicOnly' => [Constraint::Should, 'class Subject { private function helper(): void {} protected function support(): void {} }', ['name' => 'run', 'regex' => false], '%s should have only one public method named run'],
            'ShouldExactOneDifferentName' => [Constraint::Should, 'class Subject { public function other(): void {} }', ['name' => 'run', 'regex' => false], '%s should have only one public method named run'],
            'ShouldExactMultipleMatching' => [Constraint::Should, 'class Subject { public function run(): void {} public function runAgain(): void {} }', ['name' => 'run', 'regex' => false], '%s should have only one public method named run'],
            'ShouldRegexZero' => [Constraint::Should, 'class Subject {}', ['name' => '/^run/', 'regex' => true], '%s should have only one public method named /^run/'],
            'ShouldRegexConstructorOnly' => [Constraint::Should, 'class Subject { public function __construct() {} }', ['name' => '/^run/', 'regex' => true], '%s should have only one public method named /^run/'],
            'ShouldRegexOne' => [Constraint::Should, 'class Subject { public function run(): void {} }', ['name' => '/^run/', 'regex' => true], null],
            'ShouldRegexConstructorAndOne' => [Constraint::Should, 'class Subject { public function __construct() {} public function run(): void {} private function helper(): void {} protected function support(): void {} }', ['name' => '/^run/', 'regex' => true], null],
            'ShouldRegexMultiple' => [Constraint::Should, 'class Subject { public function run(): void {} public function other(): void {} }', ['name' => '/^run/', 'regex' => true], '%s should have only one public method named /^run/'],
            'ShouldRegexNonPublicOnly' => [Constraint::Should, 'class Subject { private function helper(): void {} protected function support(): void {} }', ['name' => '/^run/', 'regex' => true], '%s should have only one public method named /^run/'],
            'ShouldRegexOneDifferentName' => [Constraint::Should, 'class Subject { public function other(): void {} }', ['name' => '/^run/', 'regex' => true], '%s should have only one public method named /^run/'],
            'ShouldRegexMultipleMatching' => [Constraint::Should, 'class Subject { public function run(): void {} public function runAgain(): void {} }', ['name' => '/^run/', 'regex' => true], '%s should have only one public method named /^run/'],
            'ShouldNotExactZero' => [Constraint::ShouldNot, 'class Subject {}', ['name' => 'run', 'regex' => false], null],
            'ShouldNotExactConstructorOnly' => [Constraint::ShouldNot, 'class Subject { public function __construct() {} }', ['name' => 'run', 'regex' => false], null],
            'ShouldNotExactOne' => [Constraint::ShouldNot, 'class Subject { public function run(): void {} }', ['name' => 'run', 'regex' => false], '%s should not have only one public method named run'],
            'ShouldNotExactConstructorAndOne' => [Constraint::ShouldNot, 'class Subject { public function __construct() {} public function run(): void {} private function helper(): void {} protected function support(): void {} }', ['name' => 'run', 'regex' => false], '%s should not have only one public method named run'],
            'ShouldNotExactMultiple' => [Constraint::ShouldNot, 'class Subject { public function run(): void {} public function other(): void {} }', ['name' => 'run', 'regex' => false], null],
            'ShouldNotExactNonPublicOnly' => [Constraint::ShouldNot, 'class Subject { private function helper(): void {} protected function support(): void {} }', ['name' => 'run', 'regex' => false], null],
            'ShouldNotExactOneDifferentName' => [Constraint::ShouldNot, 'class Subject { public function other(): void {} }', ['name' => 'run', 'regex' => false], null],
            'ShouldNotExactMultipleMatching' => [Constraint::ShouldNot, 'class Subject { public function run(): void {} public function runAgain(): void {} }', ['name' => 'run', 'regex' => false], null],
            'ShouldNotRegexZero' => [Constraint::ShouldNot, 'class Subject {}', ['name' => '/^run/', 'regex' => true], null],
            'ShouldNotRegexConstructorOnly' => [Constraint::ShouldNot, 'class Subject { public function __construct() {} }', ['name' => '/^run/', 'regex' => true], null],
            'ShouldNotRegexOne' => [Constraint::ShouldNot, 'class Subject { public function run(): void {} }', ['name' => '/^run/', 'regex' => true], '%s should not have only one public method named /^run/'],
            'ShouldNotRegexConstructorAndOne' => [Constraint::ShouldNot, 'class Subject { public function __construct() {} public function run(): void {} private function helper(): void {} protected function support(): void {} }', ['name' => '/^run/', 'regex' => true], '%s should not have only one public method named /^run/'],
            'ShouldNotRegexMultiple' => [Constraint::ShouldNot, 'class Subject { public function run(): void {} public function other(): void {} }', ['name' => '/^run/', 'regex' => true], null],
            'ShouldNotRegexNonPublicOnly' => [Constraint::ShouldNot, 'class Subject { private function helper(): void {} protected function support(): void {} }', ['name' => '/^run/', 'regex' => true], null],
            'ShouldNotRegexOneDifferentName' => [Constraint::ShouldNot, 'class Subject { public function other(): void {} }', ['name' => '/^run/', 'regex' => true], null],
            'ShouldNotRegexMultipleMatching' => [Constraint::ShouldNot, 'class Subject { public function run(): void {} public function runAgain(): void {} }', ['name' => '/^run/', 'regex' => true], null],
        ];
    }

    protected function getRule(): Rule
    {
        $parser = $this->createMock(TestParser::class);
        $parser->method('__invoke')->willReturn([$this->definition]);

        return new HasOnlyOnePublicMethodNamedRule(
            new StatementBuilder($parser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
