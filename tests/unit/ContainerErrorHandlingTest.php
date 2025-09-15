<?php declare(strict_types=1);

namespace PHPat\Test\Unit;

use PHPat\Parser\ContainerException;
use PHPat\Parser\PHPStanContainerWrapper;
use PHPat\Parser\PsrContainerWrapper;
use PHPat\Parser\ServiceNotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Tests for enhanced container error handling functionality.
 *
 * @internal
 * @coversNothing
 */
final class ContainerErrorHandlingTest extends TestCase
{
    public function testPHPStanContainerWrapperThrowsEnhancedErrorForGetService(): void
    {
        $container = new PHPStanContainerWrapper();

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Container error for service "TestService": Operation "getService" not supported for service "TestService"');

        $container->getService('TestService');
    }

    public function testPHPStanContainerWrapperThrowsEnhancedErrorForGetByType(): void
    {
        $container = new PHPStanContainerWrapper();

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Container error for service "TestClass": Operation "getByType" not supported for service "TestClass"');

        $container->getByType('TestClass');
    }

    public function testPHPStanContainerWrapperThrowsEnhancedErrorForGetParameter(): void
    {
        $container = new PHPStanContainerWrapper();

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Container error for service "testParam": Operation "getParameter" not supported for service "testParam"');

        $container->getParameter('testParam');
    }

    public function testPHPStanContainerWrapperReturnsNoServicesAvailable(): void
    {
        $container = new PHPStanContainerWrapper();

        $this->assertFalse($container->hasService('TestService'));
        $this->assertEmpty($container->findServiceNamesByType('TestClass'));
        $this->assertEmpty($container->getServicesByTag('test'));
        $this->assertEmpty($container->getParameters());
        $this->assertFalse($container->hasParameter('testParam'));
    }

    public function testPsrContainerWrapperThrowsServiceNotFoundExceptionForMissingService(): void
    {
        $phpstanContainer = new PHPStanContainerWrapper();
        $container = new PsrContainerWrapper($phpstanContainer);

        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessage('Service "NonExistentService" not found in container.');

        $container->get('NonExistentService');
    }

    public function testPsrContainerWrapperHasMethodReturnsFalseForMissingService(): void
    {
        $phpstanContainer = new PHPStanContainerWrapper();
        $container = new PsrContainerWrapper($phpstanContainer);

        $this->assertFalse($container->has('NonExistentService'));
    }

    public function testContainerExceptionProvidesDetailedErrorContext(): void
    {
        $serviceId = 'TestService';
        $message = 'Service configuration error';
        $context = 'Check your container setup';

        $exception = new ContainerException($serviceId, $message, $context);

        $this->assertInstanceOf(ContainerExceptionInterface::class, $exception);
        $this->assertEquals($serviceId, $exception->getServiceId());
        $this->assertEquals($context, $exception->getContext());
        $this->assertStringContainsString($serviceId, $exception->getMessage());
        $this->assertStringContainsString($message, $exception->getMessage());
        $this->assertStringContainsString($context, $exception->getMessage());
    }

    public function testServiceNotFoundExceptionProvidesDetailedTroubleshootingInfo(): void
    {
        $serviceId = 'TestService';
        $availableServices = ['ServiceA', 'ServiceB', 'TestServiceHelper'];

        $exception = ServiceNotFoundException::withSuggestion($serviceId, $availableServices);

        $this->assertInstanceOf(NotFoundExceptionInterface::class, $exception);
        $this->assertEquals($serviceId, $exception->getServiceId());
        $this->assertEquals($availableServices, $exception->getAvailableServices());
        $this->assertStringContainsString('Troubleshooting steps:', $exception->getMessage());
        $this->assertStringContainsString('Available services in container:', $exception->getMessage());
        $this->assertStringContainsString('ServiceA', $exception->getMessage());
    }

    public function testServiceNotFoundExceptionWithSimilarServiceSuggestion(): void
    {
        $serviceId = 'TestService';
        $availableServices = ['TestServiceHelper', 'UserService', 'TestRepository'];

        $exception = ServiceNotFoundException::withSuggestion($serviceId, $availableServices);

        // Should suggest TestServiceHelper as it's most similar to TestService
        $this->assertStringContainsString('Did you mean "TestServiceHelper"?', $exception->getMessage());
    }

    public function testServiceNotFoundExceptionWithNoAvailableServices(): void
    {
        $serviceId = 'TestService';
        $availableServices = [];

        $exception = new ServiceNotFoundException($serviceId, $availableServices);

        $this->assertStringContainsString('No services are available in the current container', $exception->getMessage());
        $this->assertStringContainsString('no PSR-11 container is configured', $exception->getMessage());
    }

    public function testContainerExceptionFactoryMethods(): void
    {
        $serviceId = 'TestService';

        // Test serviceNotFound factory method
        $notFoundException = ContainerException::serviceNotFound($serviceId, 'Additional info');
        $this->assertInstanceOf(ContainerException::class, $notFoundException);
        $this->assertEquals($serviceId, $notFoundException->getServiceId());
        $this->assertStringContainsString('not found in container', $notFoundException->getMessage());
        $this->assertStringContainsString('Additional info', $notFoundException->getMessage());

        // Test resolutionFailed factory method
        $cause = new \RuntimeException('Original error');
        $resolutionException = ContainerException::resolutionFailed($serviceId, $cause, 'Resolution info');
        $this->assertInstanceOf(ContainerException::class, $resolutionException);
        $this->assertEquals($serviceId, $resolutionException->getServiceId());
        $this->assertSame($cause, $resolutionException->getPrevious());
        $this->assertStringContainsString('Failed to resolve service', $resolutionException->getMessage());
        $this->assertStringContainsString('Resolution info', $resolutionException->getMessage());

        // Test operationNotSupported factory method
        $operationException = ContainerException::operationNotSupported($serviceId, 'getService');
        $this->assertInstanceOf(ContainerException::class, $operationException);
        $this->assertEquals($serviceId, $operationException->getServiceId());
        $this->assertStringContainsString('Operation "getService" not supported', $operationException->getMessage());
        $this->assertStringContainsString('PHPStan 2.x removed container access', $operationException->getMessage());
    }
}
