<?php

namespace App\ValueObjects;

abstract class ValueObject
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function is($value): bool
    {
        return ($this->value === $value);
    }

    public function isNot($value): bool
    {
        return !$this->is($value);
    }
}
