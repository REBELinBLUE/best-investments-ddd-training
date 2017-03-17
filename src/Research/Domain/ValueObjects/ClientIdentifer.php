<?php

namespace App\Research\Domain\ValueObjects;

class ClientIdentifer
{
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}
