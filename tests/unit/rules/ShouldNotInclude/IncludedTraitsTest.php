<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotInclude;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\IncludeTrait\IncludedTraitsRule;
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

    private const SUBJECT = 'Fixture\ShouldNotInclude\IncludedTraitsTest\Subject';
    private const TARGET = 'Fixture\ShouldNotInclude\IncludedTraitsTest\Target';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotInclude\IncludedTraitsTest;
            trait Target {}
            class Subject
            {
                use Target;
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not include %s', self::SUBJECT, self::TARGET), 4],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'include',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::TARGET, false)]
        );

        return new IncludedTraitsRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
