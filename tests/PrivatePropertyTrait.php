<?php

namespace BestInvestments\Tests;

use ReflectionClass;

// FIXME: Shouldn't really need this, clean up places it is used
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
