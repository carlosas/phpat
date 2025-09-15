<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Configuration;
use PHPat\ShouldNotHappenException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @internal
 * @coversNothing
 */
final class DependencyResolverTest extends TestCase
{
    private DependencyResolver $resolver;
    private ContainerInterface $container;

    protected function setUp(): void
    {
        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $this->container = $container;
        $configuration = new Configuration(false, false, false, $this->container);
        $this->resolver = new DependencyResolver($configuration);
    }

    public function testResolveTypedParameter(): void
    {
        $mockService = new \stdClass();
        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('stdClass')
            ->willReturn($mockService)
        ;

        $parameter = $this->createParameterMock('service', 'stdClass', false, false);
        $result = $this->resolver->resolve($parameter);

        $this->assertSame($mockService, $result);
    }

    public function testResolveOptionalParameterWhenServiceNotFound(): void
    {
        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('stdClass')
            ->willThrowException(new class('Service not found') extends \Exception implements NotFoundExceptionInterface {})
        ;

        $parameter = $this->createParameterMock('service', 'stdClass', true, false, 'default_value');
        $result = $this->resolver->resolve($parameter);

        $this->assertEquals('default_value', $result);
    }

    public function testResolveRequiredParameterWhenServiceNotFound(): void
    {
        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('stdClass')
            ->willThrowException(new class('Service not found') extends \Exception implements NotFoundExceptionInterface {})
        ;

        $parameter = $this->createParameterMock('service', 'stdClass', false, false);

        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('Cannot resolve parameter "service" of type "stdClass": service not found in container');

        $this->resolver->resolve($parameter);
    }

    public function testResolveParameterWithContainerException(): void
    {
        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('stdClass')
            ->willThrowException(new class('Container error') extends \Exception implements ContainerExceptionInterface {})
        ;

        $parameter = $this->createParameterMock('service', 'stdClass', false, false);

        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('Error resolving parameter "service" of type "stdClass": Container error');

        $this->resolver->resolve($parameter);
    }

    public function testResolveParameterWithNoContainer(): void
    {
        $configuration = new Configuration(false, false, false, null);
        $resolver = new DependencyResolver($configuration);

        $parameter = $this->createParameterMock('service', 'stdClass', false, false);

        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('Cannot resolve parameter "service": no container configured and parameter is not optional');

        $resolver->resolve($parameter);
    }

    public function testResolveOptionalParameterWithNoContainer(): void
    {
        $configuration = new Configuration(false, false, false, null);
        $resolver = new DependencyResolver($configuration);

        $parameter = $this->createParameterMock('service', 'stdClass', true, false, 'default_value');
        $result = $resolver->resolve($parameter);

        $this->assertEquals('default_value', $result);
    }

    public function testResolveParameterWithNoTypeHint(): void
    {
        $parameter = $this->createParameterMock('service', null, false, false);

        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('Cannot resolve parameter "service": parameter has no type hint');

        $this->resolver->resolve($parameter);
    }

    public function testResolveOptionalParameterWithNoTypeHint(): void
    {
        $parameter = $this->createParameterMock('service', null, true, false, 'default_value');
        $result = $this->resolver->resolve($parameter);

        $this->assertEquals('default_value', $result);
    }

    public function testResolveBuiltinTypeParameter(): void
    {
        $parameter = $this->createParameterMock('name', 'string', false, true);

        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('Cannot resolve parameter "name": built-in type "string" cannot be resolved from container');

        $this->resolver->resolve($parameter);
    }

    public function testResolveOptionalBuiltinTypeParameter(): void
    {
        $parameter = $this->createParameterMock('name', 'string', true, true, 'default_name');
        $result = $this->resolver->resolve($parameter);

        $this->assertEquals('default_name', $result);
    }

    public function testResolveUnionTypeParameter(): void
    {
        $parameter = $this->createUnionTypeParameterMock('value', false);

        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('Cannot resolve parameter "value": union types are not supported for dependency injection');

        $this->resolver->resolve($parameter);
    }

    public function testResolveOptionalUnionTypeParameter(): void
    {
        $parameter = $this->createUnionTypeParameterMock('value', true, 'default_value');
        $result = $this->resolver->resolve($parameter);

        $this->assertEquals('default_value', $result);
    }

    public function testCanResolveTypedParameterWithAvailableService(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with('stdClass')
            ->willReturn(true)
        ;

        $parameter = $this->createParameterMock('service', 'stdClass', false, false);
        $result = $this->resolver->canResolve($parameter);

        $this->assertTrue($result);
    }

    public function testCanResolveTypedParameterWithUnavailableService(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with('stdClass')
            ->willReturn(false)
        ;

        $parameter = $this->createParameterMock('service', 'stdClass', false, false);
        $result = $this->resolver->canResolve($parameter);

        $this->assertFalse($result);
    }

    public function testCanResolveOptionalParameterWithUnavailableService(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with('stdClass')
            ->willReturn(false)
        ;

        $parameter = $this->createParameterMock('service', 'stdClass', true, false);
        $result = $this->resolver->canResolve($parameter);

        $this->assertTrue($result); // Optional parameters can always be resolved
    }

    public function testCanResolveWithNoContainer(): void
    {
        $configuration = new Configuration(false, false, false, null);
        $resolver = new DependencyResolver($configuration);

        $parameter = $this->createParameterMock('service', 'stdClass', false, false);
        $result = $resolver->canResolve($parameter);

        $this->assertFalse($result);
    }

    public function testCanResolveOptionalParameterWithNoContainer(): void
    {
        $configuration = new Configuration(false, false, false, null);
        $resolver = new DependencyResolver($configuration);

        $parameter = $this->createParameterMock('service', 'stdClass', true, false);
        $result = $resolver->canResolve($parameter);

        $this->assertTrue($result);
    }

    public function testCanResolveParameterWithNoTypeHint(): void
    {
        $parameter = $this->createParameterMock('service', null, false, false);
        $result = $this->resolver->canResolve($parameter);

        $this->assertFalse($result);
    }

    public function testCanResolveOptionalParameterWithNoTypeHint(): void
    {
        $parameter = $this->createParameterMock('service', null, true, false);
        $result = $this->resolver->canResolve($parameter);

        $this->assertTrue($result);
    }

    public function testCanResolveBuiltinTypeParameter(): void
    {
        $parameter = $this->createParameterMock('name', 'string', false, true);
        $result = $this->resolver->canResolve($parameter);

        $this->assertFalse($result);
    }

    public function testCanResolveOptionalBuiltinTypeParameter(): void
    {
        $parameter = $this->createParameterMock('name', 'string', true, true);
        $result = $this->resolver->canResolve($parameter);

        $this->assertTrue($result);
    }

    public function testCanResolveUnionTypeParameter(): void
    {
        $parameter = $this->createUnionTypeParameterMock('value', false);
        $result = $this->resolver->canResolve($parameter);

        $this->assertFalse($result);
    }

    public function testCanResolveOptionalUnionTypeParameter(): void
    {
        $parameter = $this->createUnionTypeParameterMock('value', true);
        $result = $this->resolver->canResolve($parameter);

        $this->assertTrue($result);
    }

    public function testCanResolveWithContainerException(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with('stdClass')
            ->willThrowException(new class('Container error') extends \Exception implements ContainerExceptionInterface {})
        ;

        $parameter = $this->createParameterMock('service', 'stdClass', true, false);
        $result = $this->resolver->canResolve($parameter);

        $this->assertTrue($result); // Falls back to optional check
    }

    /**
     * @param mixed $defaultValue
     */
    private function createParameterMock(
        string $name,
        ?string $typeName,
        bool $isOptional,
        bool $isBuiltin,
        $defaultValue = null
    ): \ReflectionParameter {
        /** @var MockObject&\ReflectionParameter $parameter */
        $parameter = $this->createMock(\ReflectionParameter::class);
        $parameter->method('getName')->willReturn($name);
        $parameter->method('isOptional')->willReturn($isOptional);

        if ($defaultValue !== null) {
            $parameter->method('getDefaultValue')->willReturn($defaultValue);
        }

        if ($typeName === null) {
            $parameter->method('getType')->willReturn(null);
        } else {
            /** @var MockObject&\ReflectionNamedType $type */
            $type = $this->createMock(\ReflectionNamedType::class);
            $type->method('getName')->willReturn($typeName);
            $type->method('isBuiltin')->willReturn($isBuiltin);
            $parameter->method('getType')->willReturn($type);
        }

        return $parameter;
    }

    /**
     * @param mixed $defaultValue
     */
    private function createUnionTypeParameterMock(
        string $name,
        bool $isOptional,
        $defaultValue = null
    ): \ReflectionParameter {
        /** @var MockObject&\ReflectionParameter $parameter */
        $parameter = $this->createMock(\ReflectionParameter::class);
        $parameter->method('getName')->willReturn($name);
        $parameter->method('isOptional')->willReturn($isOptional);

        if ($defaultValue !== null) {
            $parameter->method('getDefaultValue')->willReturn($defaultValue);
        }

        /** @var MockObject&\ReflectionUnionType $type */
        $type = $this->createMock(\ReflectionUnionType::class);
        $parameter->method('getType')->willReturn($type);

        return $parameter;
    }
}
