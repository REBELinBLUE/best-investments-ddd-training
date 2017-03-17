<?php

namespace App\Research\Domain\ValueObjects;

class ManagerIdentifer
{
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}
