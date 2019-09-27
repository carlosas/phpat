<?php declare(strict_types=1);

namespace PhpAT;

use PhpAT\Rule\Rule;
use PhpAT\Rule\RuleCollection;
use PhpAT\Statement\Statement;
use PhpAT\Statement\StatementBuilder;
use PhpAT\Shared\EventSubscriber;
use PhpAT\Test\TestExtractor;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class App
{
    /** @var TestExtractor $extractor */
    private $extractor;
    /** @var StatementBuilder $statementBuilder */
    private $statementBuilder;
    /** @var EventDispatcherInterface */
    private $dispatcher;
    /** @var EventSubscriber */
    private $subscriber;

    public function __construct(
        TestExtractor $extractor,
        StatementBuilder $statementBuilder,
        EventDispatcherInterface $dispatcher,
        EventSubscriberInterface $subscriber
    ) {
        $this->extractor = $extractor;
        $this->statementBuilder = $statementBuilder;
        $this->dispatcher = $dispatcher;
        $this->subscriber = $subscriber;
    }

    /** @throws \Exception */
    public function execute(): void
    {
        try {
            $this->dispatcher->addSubscriber($this->subscriber);

            $testSuite = $this->extractor->execute();

            $rules = new RuleCollection();
            foreach ($testSuite->getValues() as $test) {
                $rules = $rules->merge($test());
            }
            $this->exposeLogo();

            foreach ($rules->getValues() as $rule) {
                $statements = $this->statementBuilder->build($rule);
                $this->exposeRuleName($rule);
                /** @var Statement $statement */
                foreach ($statements as $statement) {
                    $this->validateStatement($statement);
                }
                echo PHP_EOL;
            }
        } catch (\Exception $e) {
            $this->exposeFatalAndExit($e->getMessage());
        }

        if ($this->subscriber->thereWereErrors()) {
            throw new \Exception();
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
        echo PHP_EOL . 'RULE: ' . $rule->getName() . PHP_EOL;
    }

    private function validateStatement(Statement $statement): void
    {
        $statement->getType()->validate(
            $statement->getParsedClass(),
            $statement->getDestination(),
            $statement->getDestinationExcluded(),
            $statement->isInverse()
        );
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
}
