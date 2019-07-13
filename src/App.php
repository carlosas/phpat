<?php declare(strict_types=1);

namespace PHPArchiTest;

use PHPArchiTest\Rule\RuleCollection;
use PHPArchiTest\Statement\Statement;
use PHPArchiTest\Statement\StatementBuilder;
use PHPArchiTest\Test\TestExtractor;
use PHPArchiTest\Validation\TestError;
use PHPArchiTest\Validation\TestErrorCollection;
use PHPArchiTest\Validation\Validator;

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
            $statements = $this->statementBuilder->build($rules);

            $this->exposeLogo();
            $errors = new TestErrorCollection();
            /** @var Statement $statement */
            foreach ($statements as $statement) {
                $isValid = $this->validator->validate($statement);
                //echo $statement->getOrigin()->getName()." -> ".$statement->getDestination()->getName();
                $this->exposeValidation($isValid);
                if (false === $isValid) {
                    $shouldOrNot = $statement->isInverse() ? ' should not ' : ' should ';
                    $msg = $statement->getOrigin()->getName()
                        .$shouldOrNot.$statement->getType()->getMessageVerb()
                        .' '.$statement->getDestination()->getName();
                    $errors->addValue(new TestError($statement->getName(), $msg));
                }
            }
        } catch (\ReflectionException $e) {
            $this->exposeFatalAndExit($e->getMessage(), $e->getTrace()[1]['file']);
        } catch (\Exception $e) {
            $this->exposeFatalAndExit($e->getMessage());
        }

        if ($errors->hasValues()) {
            $this->exposeErrors($errors);
            throw new \Exception();
        } else {
            $this->exposeSuccess();
        }
    }

    private function exposeLogo(): void
    {
        echo '-----/------\-----|----\---/'.PHP_EOL;
        echo '----/---PHPArchiTest----\-/-'.PHP_EOL;
        echo '---/----------\---|------X--'.PHP_EOL;
        echo PHP_EOL;
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
        echo ('FATAL ERROR: '.$message);
        if (!is_null($trace)) {
            echo ' in '.$trace;
        }
        echo PHP_EOL;

        throw new \Exception();
    }

    private function exposeErrors(TestErrorCollection $errors): void
    {
        $lastTest = '';
        echo PHP_EOL;
        /** @var TestError $error */
        foreach ($errors->getValues() as $error) {
            if ($error->getTestName() !== $lastTest) {
                 echo PHP_EOL.'ERROR: '.$error->getTestName().PHP_EOL;
                $lastTest = $error->getTestName();
            }
            echo $error->getMessage().PHP_EOL;
        }
    }

    private function exposeSuccess(): void
    {
        echo PHP_EOL.PHP_EOL.'OK'.PHP_EOL;
    }
}
