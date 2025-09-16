<?php declare(strict_types=1);

namespace PHPat\Test\Integration;

use PHPat\Configuration;
use PHPat\Parser\PHPStanContainerWrapper;
use PHPat\Parser\PsrContainerWrapper;
use PHPat\Test\DependencyResolver;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for enhanced container error handling in realistic scenarios.
 */
final class ContainerErrorHandlingIntegrationTest extends TestCase
{
    public function testDependencyResolverWithEnhancedErrorHandling(): void
    {
        // Create a configuration with the PHPStan fallback container
        $phpstanContainer = new PHPStanContainerWrapper();
        $psrContainer = new PsrContainerWrapper($phpstanContainer);
        $configuration = new Configuration(false, false, false, $psrContainer);
        
        $dependencyResolver = new DependencyResolver($configuration);

        // Create a reflection parameter for a non-existent service
        $reflectionClass = new \ReflectionClass(TestClassWithDependency::class);
        $constructor = $reflectionClass->getConstructor();
        $parameters = $constructor->getParameters();
        $serviceParameter = $parameters[0]; // NonExistentService $service

        // Test that canResolve returns false for non-existent service
        $this->assertFalse($dependencyResolver->canResolve($serviceParameter));

        // Test that resolve throws a detailed exception
        $this->expectException(\PHPat\ShouldNotHappenException::class);
        $this->expectExceptionMessage('Cannot resolve parameter "service" of type "PHPat\Test\Integration\NonExistentService"');
        $this->expectExceptionMessage('service not found in container');

        $dependencyResolver->resolve($serviceParameter);
    }

    public function testDependencyResolverWithOptionalParameter(): void
    {
        // Create a configuration with the PHPStan fallback container
        $phpstanContainer = new PHPStanContainerWrapper();
        $psrContainer = new PsrContainerWrapper($phpstanContainer);
        $configuration = new Configuration(false, false, false, $psrContainer);
        
        $dependencyResolver = new DependencyResolver($configuration);

        // Create a reflection parameter for an optional service
        $reflectionClass = new \ReflectionClass(TestClassWithOptionalDependency::class);
        $constructor = $reflectionClass->getConstructor();
        $parameters = $constructor->getParameters();
        $optionalParameter = $parameters[0]; // ?NonExistentService $service = null

        // Test that canResolve returns true for optional parameter
        $this->assertTrue($dependencyResolver->canResolve($optionalParameter));

        // Test that resolve returns the default value
        $result = $dependencyResolver->resolve($optionalParameter);
        $this->assertNull($result);
    }
}

/**
 * Test class with a required dependency for testing error handling.
 */
class TestClassWithDependency
{
    public function __construct(NonExistentService $service)
    {
    }
}

/**
 * Test class with an optional dependency for testing error handling.
 */
class TestClassWithOptionalDependency
{
    public function __construct(?NonExistentService $service = null)
    {
    }
}

/**
 * Non-existent service class for testing error scenarios.
 */
class NonExistentService
{
}