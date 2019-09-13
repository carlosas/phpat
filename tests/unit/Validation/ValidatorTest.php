<?php

namespace Tests\PhpAT\Validation;

use PhpAT\Rule\Type\RuleType;
use PhpAT\Statement\Statement;
use PhpAT\Statement\StatementNotValidException;
use PhpAT\Validation\Validator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /** @var Validator */
    private $class;
    /** @var MockObject */
    private $ruleType;
    /** @var MockObject */
    private $statement;
    
    public function setUp()
    {
        $this->ruleType = $this->createMock(RuleType::class);
        $this->statement = $this->createMock(Statement::class);
        $this->class = new Validator();
    }
    
    public function testValidatesStatement(): void
    {
        $this->ruleType->method('validate')->willReturn(true);
        $this->statement->method('isInverse')->willReturn(false);
        $this->statement->method('getType')->willReturn($this->ruleType);

        $this->expectNotToPerformAssertions();
        $this->class->validate($this->statement);
    }
    
    public function testValidatesInverseStatement(): void
    {
        $this->ruleType->method('validate')->willReturn(false);
        $this->statement->method('isInverse')->willReturn(true);
        $this->statement->method('getType')->willReturn($this->ruleType);

        $this->expectNotToPerformAssertions();
        $this->class->validate($this->statement);
    }
    
    public function testFailsStatement(): void
    {
        $this->ruleType->method('validate')->willReturn(false);
        $this->statement->method('isInverse')->willReturn(false);
        $this->statement->method('getType')->willReturn($this->ruleType);

        $this->expectException(StatementNotValidException::class);
        $this->class->validate($this->statement);
    }
    
    public function testFailsInverseStatement(): void
    {
        $this->ruleType->method('validate')->willReturn(true);
        $this->statement->method('isInverse')->willReturn(true);
        $this->statement->method('getType')->willReturn($this->ruleType);

        $this->expectException(StatementNotValidException::class);
        $this->class->validate($this->statement);
    }
}
