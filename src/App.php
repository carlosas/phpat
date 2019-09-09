<?php declare(strict_types=1);

namespace PhpAT;

use PhpAT\Rule\Rule;
use PhpAT\Rule\RuleCollection;
use PhpAT\Statement\Statement;
use PhpAT\Statement\StatementBuilder;
use PhpAT\Statement\StatementNotValidException;
use PhpAT\Test\TestExtractor;
use PhpAT\Validation\ValidationError;
use PhpAT\Validation\ValidationErrorCollection;
use PhpAT\Validation\Validator;

class App
{
    /** @var TestExtractor $extractor */
    private $extractor;
    /** @var StatementBuilder $statementBuilder */
    private $statementBuilder;
    /** @var Validator $validator */
    private $validator;

    public function __construct(TestExtractor $extractor, StatementBuilder $statementBuilder, Validator $validator)
    {
        $this->extractor = $extractor;
        $this->statementBuilder = $statementBuilder;
        $this->validator = $validator;
    }

    /** @throws \Exception */
    public function execute(): void
    {
        try {
            $testSuite = $this->extractor->execute();

            $rules = new RuleCollection();
            foreach ($testSuite->getValues() as $test) {
                $rules = $rules->merge($test());
            }
            $this->exposeLogo();
            $errors = new ValidationErrorCollection();

            foreach ($rules->getValues() as $rule) {
                $statements = $this->statementBuilder->build($rule);
                $this->exposeRuleName($rule);
                /** @var Statement $statement */
                foreach ($statements as $statement) {
                    try {
                        $this->validator->validate($statement);
                        $this->exposeValidation(true);
                    } catch (StatementNotValidException $error) {
                        $errors->addValue(new ValidationError($statement->getErrorMessage()));
                        $this->exposeValidation(false);
                    }
                }
                echo PHP_EOL;
                $this->exposeErrors($errors);
            }
        } catch (\Exception $e) {
            $this->exposeFatalAndExit($e->getMessage());
        }

        if ($errors->hasValues()) {
            throw new \Exception();
        } else {
            $this->exposeSuccess();
        }
    }

    private function exposeLogo(): void
    {
        echo '---/-------\------|-----\---/--' . PHP_EOL;
        echo '--/-PHP Architecture Tester/---' . PHP_EOL;
        echo '-/-----------\----|-------X----' . PHP_EOL;
        echo PHP_EOL;
    }

    private function exposeRuleName(Rule $rule): void
    {
        echo 'RULE: ' . $rule->getName() . PHP_EOL;
    }

    private function exposeValidation(bool $success): void
    {
        echo $success ? '.' : 'X';
    }

    /**
     * @throws \Exception
     */
    private function exposeFatalAndExit(string $message, string $trace = null): void
    {
        echo ('FATAL ERROR: ' . $message);
        if (!is_null($trace)) {
            echo ' in ' . $trace;
        }
        echo PHP_EOL;

        throw new \Exception();
    }

    private function exposeErrors(ValidationErrorCollection $errors): void
    {
        /** @var ValidationError $error */
        foreach ($errors->getValues() as $error) {
            echo $error->getMessage();
        }
    }

    private function exposeSuccess(): void
    {
        echo PHP_EOL . PHP_EOL . 'OK' . PHP_EOL;
    }
}
