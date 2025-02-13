<?php

namespace Tests\Supports;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

trait Reflection
{
    /**
     * @throws ReflectionException
     */
    protected function method(string $name, object $object): ReflectionMethod
    {
        $reflection = new ReflectionClass($object);
        $method = $reflection->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @throws ReflectionException
     */
    protected function property(string $name, object $object): ReflectionProperty
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * @throws ReflectionException
     */
    protected function getPropertyValue(string $propertyName, object $object)
    {
        return $this->property($propertyName, $object)->getValue($object);
    }

    /**
     * @throws ReflectionException
     */
    protected function setPropertyValue(string $propertyName, $value, $object): void
    {
        $this->property($propertyName, $object)->setValue($object, $value);
    }
}
