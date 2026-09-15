<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\OnePublicMethod;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\OnePublicMethod\HasOnlyOnePublicMethodRule;
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
 * @extends RuleTestCase<HasOnlyOnePublicMethodRule>
 * @internal
 * @coversNothing
 */
class HasOnlyOnePublicMethodRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private RelationRule $definition;

    #[DataProvider('assertionCases')]
    public function testAssertion(Constraint $constraint, string $code, ?string $message): void
    {
        $namespace = 'Fixture\Declaration\OnePublicMethod\\'.$this->dataName();
        $subject = $namespace.'\Subject';
        $selected = PHPat::rule()->classes(Selector::classname($subject));
        $step = $constraint === Constraint::Should ? $selected->should() : $selected->shouldNot();
        $this->definition = $step->haveOnlyOnePublicMethod()();
        $this->definition->ruleName = 'test';
        self::assertSame($constraint, $this->definition->getConstraint());
        self::assertSame('haveOnlyOnePublicMethod', $this->definition->getAssertionType());
        $file = $this->createPhpFile("<?php\nnamespace ".$namespace.";\n".$code);

        $this->analyse([$file], $message === null ? [] : [[sprintf($message, $subject, $namespace), 3]]);
    }

    /**
     * @return array<string, array{Constraint, string, ?string}>
     */
    public static function assertionCases(): array
    {
        return [
            'ShouldZero' => [Constraint::Should, 'class Subject {}', '%s should have only one public method'],
            'ShouldConstructorOnly' => [Constraint::Should, 'class Subject { public function __construct() {} }', '%s should have only one public method'],
            'ShouldOne' => [Constraint::Should, 'class Subject { public function run(): void {} }', null],
            'ShouldConstructorAndOne' => [Constraint::Should, 'class Subject { public function __construct() {} public function run(): void {} private function helper(): void {} protected function support(): void {} }', null],
            'ShouldMultiple' => [Constraint::Should, 'class Subject { public function run(): void {} public function other(): void {} }', '%s should have only one public method'],
            'ShouldNonPublicOnly' => [Constraint::Should, 'class Subject { private function helper(): void {} protected function support(): void {} }', '%s should have only one public method'],
            'ShouldNotZero' => [Constraint::ShouldNot, 'class Subject {}', null],
            'ShouldNotConstructorOnly' => [Constraint::ShouldNot, 'class Subject { public function __construct() {} }', null],
            'ShouldNotOne' => [Constraint::ShouldNot, 'class Subject { public function run(): void {} }', '%s should not have only one public method'],
            'ShouldNotConstructorAndOne' => [Constraint::ShouldNot, 'class Subject { public function __construct() {} public function run(): void {} private function helper(): void {} protected function support(): void {} }', '%s should not have only one public method'],
            'ShouldNotMultiple' => [Constraint::ShouldNot, 'class Subject { public function run(): void {} public function other(): void {} }', null],
            'ShouldNotNonPublicOnly' => [Constraint::ShouldNot, 'class Subject { private function helper(): void {} protected function support(): void {} }', null],
        ];
    }

    protected function getRule(): Rule
    {
        $parser = $this->createMock(TestParser::class);
        $parser->method('__invoke')->willReturn([$this->definition]);

        return new HasOnlyOnePublicMethodRule(
            new StatementBuilder($parser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
