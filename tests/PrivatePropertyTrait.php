<?php

namespace BestInvestments\Tests;

use ReflectionClass;

trait PrivatePropertyTrait
{
    protected function getInnerPropertyValueByReflection($instance, $property)
    {
        $reflected = new ReflectionClass($instance);

        $reflectedProperty = $reflected->getProperty($property);
        $reflectedProperty->setAccessible(true);

        return $reflectedProperty->getValue($instance);
    }
}
