<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\ApplyAttribute;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\ApplyAttribute\ClassAttributeRule;
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
 * @extends RuleTestCase<ClassAttributeRule>
 * @internal
 * @coversNothing
 */
class ClassAttributeRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private RelationRule $definition;

    /**
     * @param array<string, bool|string> $params
     */
    #[DataProvider('assertionCases')]
    public function testAssertion(Constraint $constraint, string $code, array $params, ?string $message): void
    {
        $namespace = 'Fixture\Relation\ApplyAttribute\\'.$this->dataName();
        $subject = $namespace.'\Subject';
        $selected = PHPat::rule()->classes(Selector::classname($subject));
        $step = $constraint === Constraint::Should ? $selected->should() : $selected->shouldNot();
        $target = Selector::classname($namespace.'\Target');
        $assertion = $step->applyAttribute()->classes($target);
        $this->definition = ($params['exclude'] ? $assertion->excluding($target) : $assertion)();
        self::assertSame([$target], $this->definition->getTargets());
        self::assertSame($params['exclude'] ? [$target] : [], $this->definition->getTargetExcludes());
        $this->definition->ruleName = 'test';
        self::assertSame($constraint, $this->definition->getConstraint());
        self::assertSame('applyAttribute', $this->definition->getAssertionType());
        $attributes = <<<'PHP'
            #[\Attribute]
            class Target {}
            #[\Attribute]
            class Other {}
            PHP;
        $code = $attributes."\n".$code;
        $file = $this->createPhpFile("<?php\nnamespace ".$namespace.";\n".$code);

        $this->analyse([$file], $message === null ? [] : [[sprintf($message, $subject, $namespace), 7]]);
    }

    /**
     * @return array<string, array{Constraint, string, array<string, bool|string>, ?string}>
     */
    public static function assertionCases(): array
    {
        return [
            'ShouldAbsent' => [Constraint::Should, 'class Subject {}', ['exclude' => false], '%1$s should apply the attribute %2$s\Target'],
            'ShouldPresent' => [Constraint::Should, '#[Target] class Subject {}', ['exclude' => false], null],
            'ShouldOther' => [Constraint::Should, '#[Other] class Subject {}', ['exclude' => false], '%1$s should apply the attribute %2$s\Target'],
            'ShouldBoth' => [Constraint::Should, '#[Target, Other] class Subject {}', ['exclude' => false], null],
            'ShouldNotAbsent' => [Constraint::ShouldNot, 'class Subject {}', ['exclude' => false], null],
            'ShouldNotPresent' => [Constraint::ShouldNot, '#[Target] class Subject {}', ['exclude' => false], '%1$s should not apply the attribute %2$s\Target'],
            'ShouldNotOther' => [Constraint::ShouldNot, '#[Other] class Subject {}', ['exclude' => false], null],
            'ShouldNotBoth' => [Constraint::ShouldNot, '#[Target, Other] class Subject {}', ['exclude' => false], '%1$s should not apply the attribute %2$s\Target'],
            'ShouldNotExcluded' => [Constraint::ShouldNot, '#[Target] class Subject {}', ['exclude' => true], null],
        ];
    }

    protected function getRule(): Rule
    {
        $parser = $this->createMock(TestParser::class);
        $parser->method('__invoke')->willReturn([$this->definition]);

        return new ClassAttributeRule(
            new StatementBuilder($parser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
