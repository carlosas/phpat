<?php declare(strict_types=1);

namespace Tests\PHPat\unit\features;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\IsFinal\IsFinalRule;
use PHPat\Rule\Assertion\Relation\Depend\ParentClassRule;
use PHPat\Selector\Selector;
use PHPat\Statement\StatementBuilder;
use PHPat\Test\PHPat;
use PHPat\Test\TestParser;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;

/**
 * @extends RuleTestCase<IsFinalRule|ParentClassRule>
 * @internal
 * @coversNothing
 */
class NonIgnorableTest extends RuleTestCase
{
    use CreatesPhpFile;

    private string $scenario;
    private bool $nonIgnorable;

    /**
     * @dataProvider provideRules
     */
    public function testErrorsRespectIgnorability(string $scenario, bool $nonIgnorable, bool $ignoreComment): void
    {
        $this->scenario = $scenario;
        $this->nonIgnorable = $nonIgnorable;
        $modifier = $scenario === 'declarationShouldNot' ? 'final ' : '';
        $comment = $ignoreComment ? '// @phpstan-ignore phpat.testRule' : '';
        $file = $this->createPhpFile(<<<PHP
            <?php
            namespace Fixture\\NonIgnorable;
            class Target {}
            class Other {}
            {$comment}
            {$modifier}class Subject extends Target {}
            PHP);

        $errors = $this->gatherAnalyserErrors([$file]);
        $ruleErrors = array_values(array_filter($errors, static fn ($error): bool => $error->getIdentifier() === 'phpat.testRule'));
        if ($ignoreComment && !$nonIgnorable) {
            self::assertSame([], $errors);

            return;
        }

        self::assertCount(1, $ruleErrors);
        self::assertSame(!$nonIgnorable, $ruleErrors[0]->canBeIgnored());
        self::assertSame('Architecture reason', $ruleErrors[0]->getTip());
        self::assertSame(6, $ruleErrors[0]->getLine());
    }

    /**
     * @return iterable<string, array{string, bool, bool}>
     */
    public static function provideRules(): iterable
    {
        foreach (['declarationShould', 'declarationShouldNot', 'relationShould', 'relationShouldNot', 'relationCanOnly'] as $scenario) {
            foreach ([false, true] as $nonIgnorable) {
                foreach ([false, true] as $ignoreComment) {
                    yield $scenario.'-'.(int) $nonIgnorable.'-'.(int) $ignoreComment => [$scenario, $nonIgnorable, $ignoreComment];
                }
            }
        }
    }

    protected function getRule(): Rule
    {
        $subject = PHPat::rule()->classes(Selector::classname('Fixture\NonIgnorable\Subject'));
        $builder = match ($this->scenario) {
            'declarationShould' => $subject->should()->beFinal(),
            'declarationShouldNot' => $subject->shouldNot()->beFinal(),
            'relationShould' => $subject->should()->dependOn()->classes(Selector::classname('Fixture\NonIgnorable\Other')),
            'relationShouldNot' => $subject->shouldNot()->dependOn()->classes(Selector::classname('Fixture\NonIgnorable\Target')),
            'relationCanOnly' => $subject->canOnly()->dependOn()->classes(Selector::classname('Fixture\NonIgnorable\Other')),
        };
        $builder = $builder->because('Architecture reason');
        if ($this->nonIgnorable) {
            $builder->nonIgnorable();
        }
        $rule = $builder();
        $rule->ruleName = 'testRule';
        $testParser = $this->createMock(TestParser::class);
        $testParser->method('__invoke')->willReturn([$rule]);
        $ruleClass = str_starts_with($this->scenario, 'declaration') ? IsFinalRule::class : ParentClassRule::class;

        return new $ruleClass(
            new StatementBuilder($testParser),
            new Configuration(false, false, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
