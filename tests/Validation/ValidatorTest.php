<?php
namespace Tests\PhpArchiTest\Validation;
use PHPArchiTest\Rule\RuleType;
use PHPArchiTest\Statement\Statement;
use PHPArchiTest\Validation\Validator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
class ValidatorTest extends TestCase
{
    /** @var Validator */
    private $validator;
    /** @var MockObject */
    private $ruleType;
    /** @var MockObject */
    private $statement;
    
    public function setUp()
    {
        $this->ruleType = $this->createMock(RuleType::class);
        $this->statement = $this->createMock(Statement::class);
        $this->validator = new Validator();
    }
    
    public function testValidatesStatement(): void
    {
        $this->ruleType->method('satisfies')->willReturn(true);
        $this->statement->method('isInverse')->willReturn(false);
        $this->statement->method('getType')->willReturn($this->ruleType);
        self::assertTrue($this->validator->validate($this->statement));
    }
    
    public function testValidatesInverseStatement(): void
    {
        $this->ruleType->method('satisfies')->willReturn(false);
        $this->statement->method('isInverse')->willReturn(true);
        $this->statement->method('getType')->willReturn($this->ruleType);
        self::assertTrue($this->validator->validate($this->statement));
    }
    
    public function testFailsStatement(): void
    {
        $this->ruleType->method('satisfies')->willReturn(false);
        $this->statement->method('isInverse')->willReturn(false);
        $this->statement->method('getType')->willReturn($this->ruleType);
        self::assertFalse($this->validator->validate($this->statement));
    }
    
    public function testFailsInverseStatement(): void
    {
        $this->ruleType->method('satisfies')->willReturn(true);
        $this->statement->method('isInverse')->willReturn(true);
        $this->statement->method('getType')->willReturn($this->ruleType);
        self::assertFalse($this->validator->validate($this->statement));
    }
}
