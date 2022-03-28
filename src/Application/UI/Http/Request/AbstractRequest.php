<?php

namespace App\Application\UI\Http\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

abstract class AbstractRequest implements RequestInterface
{
    private Request $originalRequest;
    private array $requestAndQueryParameters;
    private NameConverterInterface $caseConverter;
    private PropertyAccessorInterface $propertyAccessor;

    public function setOriginalRequest(Request $request): RequestInterface
    {
        $this->originalRequest = $request;

        return $this;
    }

    public function getOriginalRequest(): Request
    {
        return $this->originalRequest;
    }

    public function setCaseConverter(NameConverterInterface $caseConverter)
    {
        $this->caseConverter = $caseConverter;
    }

    public function setPropertyAccessor(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    public function setRequestAndQueryParameters(array $requestAndQueryParameters)
    {
        $this->requestAndQueryParameters = $requestAndQueryParameters;
    }

    public function getParameters(): array
    {
        return $this->computeParameters($this, $this->requestAndQueryParameters);
    }

    /**
     * Used only for var_export().
     */
    public function __debugInfo(): ?array
    {
        $result = get_object_vars($this);
        unset($result['originalRequest'], $result['_securityContext']);

        return $result;
    }

    private function computeParameters($class, array $requestAndQueryParameters): array
    {
        $classProperties = (new \ReflectionClass($class))->getProperties();

        $parameters = [];
        foreach ($classProperties as $property) {
            $normalizedPropertyName = $this->caseConverter->normalize($property->getName());
            $value = $this->propertyAccessor->getValue($class, $normalizedPropertyName);

            if (!array_key_exists($normalizedPropertyName, $requestAndQueryParameters) && $value === null) {
                continue;
            }

            $value = $this->propertyAccessor->getValue($class, $normalizedPropertyName);

            $parameters[$normalizedPropertyName] = $this->computeParameters(
                $value,
                $requestAndQueryParameters[$normalizedPropertyName]
            );
        }

        return $parameters;
    }
}
