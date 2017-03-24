<?php

namespace BestInvestments\Research\Domain\ValueObjects;

class ManagerIdentifier
{
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}