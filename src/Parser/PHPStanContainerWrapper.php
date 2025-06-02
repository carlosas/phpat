<?php declare(strict_types=1);

namespace PHPat\Parser;

use PHPStan\DependencyInjection\Container;

final class PHPStanContainerWrapper implements Container
{
    private object $object;

    public function __construct(object $object)
    {
        if (
            !method_exists($object, 'getService')
            || !method_exists($object, 'hasService')
            || !method_exists($object, 'getByType')
            || !method_exists($object, 'findServiceNamesByType')
            || !method_exists($object, 'getServicesByTag')
            || !method_exists($object, 'getParameters')
        ) {
            throw new \InvalidArgumentException('Provided container does not conform to expected PHPStan container interface.');
        }

        $this->object = $object;
    }

    public function getService(string $serviceName): object
    {
        return $this->object->getService($serviceName);
    }

    public function hasService(string $serviceName): bool
    {
        return $this->object->hasService($serviceName);
    }

    public function getByType(string $className, bool $throw = true): ?object
    {
        return $this->object->getByType($className, $throw);
    }

    public function findServiceNamesByType(string $className): array
    {
        return $this->object->findServiceNamesByType($className);
    }

    public function getServicesByTag(string $tagName): array
    {
        return $this->object->getServicesByTag($tagName);
    }

    public function getParameters(): array
    {
        return $this->object->getParameters();
    }

    public function hasParameter(string $parameterName): bool
    {
        return $this->object->hasParameter($parameterName);
    }

    public function getParameter($parameterName)
    {
        return $this->object->getParameter($parameterName);
    }
}
