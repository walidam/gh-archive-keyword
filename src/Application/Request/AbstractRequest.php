<?php

namespace App\Application\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

abstract class AbstractRequest implements RequestInterface
{
    private Request $_originalRequest;
    private array $_requestAndQueryParameters;
    private NameConverterInterface $_caseConverter;
    private PropertyAccessorInterface $_propertyAccessor;

    public function setOriginalRequest(Request $request): self
    {
        $this->_originalRequest = $request;

        return $this;
    }

    public function getOriginalRequest(): Request
    {
        return $this->_originalRequest;
    }

    public function setCaseConverter(NameConverterInterface $caseConverter)
    {
        $this->_caseConverter = $caseConverter;
    }

    public function setPropertyAccessor(PropertyAccessorInterface $propertyAccessor)
    {
        $this->_propertyAccessor = $propertyAccessor;
    }

    public function setRequestAndQueryParameters(array $requestAndQueryParameters)
    {
        $this->_requestAndQueryParameters = $requestAndQueryParameters;
    }

    public function getParameters(): array
    {
        return $this->computeParameters($this, $this->_requestAndQueryParameters);
    }

    /**
     * Used only for var_export().
     */
    public function __debugInfo(): ?array
    {
        $result = get_object_vars($this);
        unset($result['_originalRequest'], $result['_securityContext']);

        return $result;
    }

    private function computeParameters($class, array $requestAndQueryParameters): array
    {
        $classProperties = (new \ReflectionClass($class))->getProperties();

        $parameters = [];
        foreach ($classProperties as $property) {
            $normalizedPropertyName = $this->_caseConverter->normalize($property->getName());
            $value = $this->_propertyAccessor->getValue($class, $normalizedPropertyName);

            if (!array_key_exists($normalizedPropertyName, $requestAndQueryParameters) && $value === null) {
                continue;
            }

            $value = $this->_propertyAccessor->getValue($class, $normalizedPropertyName);

            $parameters[$normalizedPropertyName] = $this->computeParameters(
                $value,
                $requestAndQueryParameters[$normalizedPropertyName]
            );
        }

        return $parameters;
    }
}
