<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Configuration;
use PHPat\ShouldNotHappenException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @internal
 * @coversNothing
 */
final class TestInstantiatorTest extends TestCase
{
    private TestInstantiator $instantiator;
    private ContainerInterface $container;
    private DependencyResolver $dependencyResolver;

    protected function setUp(): void
    {
        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $this->container = $container;
        $configuration = new Configuration(false, false, false, $this->container);
        $this->dependencyResolver = new DependencyResolver($configuration);
        $this->instantiator = new TestInstantiator($this->dependencyResolver);
    }

    public function testInstantiateClassWithoutConstructor(): void
    {
        $class = new \ReflectionClass(TestClassWithoutConstructor::class);
        $instance = $this->instantiator->instantiate($class);

        $this->assertInstanceOf(TestClassWithoutConstructor::class, $instance);
    }

    public function testInstantiateClassWithEmptyConstructor(): void
    {
        $class = new \ReflectionClass(TestClassWithEmptyConstructor::class);
        $instance = $this->instantiator->instantiate($class);

        $this->assertInstanceOf(TestClassWithEmptyConstructor::class, $instance);
    }

    public function testInstantiateClassWithDependencies(): void
    {
        $mockService = new \stdClass();
        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('stdClass')
            ->willReturn($mockService)
        ;

        $class = new \ReflectionClass(TestClassWithDependency::class);
        $instance = $this->instantiator->instantiate($class);

        $this->assertInstanceOf(TestClassWithDependency::class, $instance);
        $this->assertSame($mockService, $instance->service);
    }

    public function testInstantiateClassWithOptionalParameter(): void
    {
        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('stdClass')
            ->willThrowException(new class('Service not found') extends \Exception implements NotFoundExceptionInterface {})
        ;

        $class = new \ReflectionClass(TestClassWithOptionalDependency::class);
        $instance = $this->instantiator->instantiate($class);

        $this->assertInstanceOf(TestClassWithOptionalDependency::class, $instance);
        $this->assertNull($instance->service);
    }

    public function testInstantiateClassWithUnresolvableDependency(): void
    {
        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('stdClass')
            ->willThrowException(new class('Service not found') extends \Exception implements NotFoundExceptionInterface {})
        ;

        $class = new \ReflectionClass(TestClassWithDependency::class);

        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('Cannot resolve parameter "service" of type "stdClass"');

        $this->instantiator->instantiate($class);
    }

    public function testInstantiateClassWithNoContainer(): void
    {
        $configuration = new Configuration(false, false, false, null);
        $dependencyResolver = new DependencyResolver($configuration);
        $instantiator = new TestInstantiator($dependencyResolver);

        $class = new \ReflectionClass(TestClassWithDependency::class);

        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('no container configured');

        $instantiator->instantiate($class);
    }

    public function testInstantiateClassWithBuiltinType(): void
    {
        $class = new \ReflectionClass(TestClassWithBuiltinType::class);

        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('built-in type "string" cannot be resolved');

        $this->instantiator->instantiate($class);
    }

    public function testInstantiateClassWithUnionType(): void
    {
        $class = new \ReflectionClass(TestClassWithUnionType::class);

        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('union types are not supported');

        $this->instantiator->instantiate($class);
    }

    public function testInstantiateClassWithOptionalBuiltinType(): void
    {
        $class = new \ReflectionClass(TestClassWithOptionalBuiltinType::class);
        $instance = $this->instantiator->instantiate($class);

        $this->assertInstanceOf(TestClassWithOptionalBuiltinType::class, $instance);
    }
}

// Test fixture classes
class TestClassWithoutConstructor {}

class TestClassWithEmptyConstructor
{
    public function __construct() {}
}

class TestClassWithDependency
{
    public \stdClass $service;

    public function __construct(\stdClass $service)
    {
        $this->service = $service;
    }
}

class TestClassWithOptionalDependency
{
    public ?\stdClass $service;

    public function __construct(?\stdClass $service = null)
    {
        $this->service = $service;
    }
}

class TestClassWithBuiltinType
{
    public function __construct(string $name) {}
}

class TestClassWithUnionType
{
    public function __construct(int|string $value) {}
}

class TestClassWithOptionalBuiltinType
{
    public function __construct(string $name = 'default') {}
}
