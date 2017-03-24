<?php

namespace BestInvestments\Prospecting\Domain\ValueObjects;

class ProspectIdentifier
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
