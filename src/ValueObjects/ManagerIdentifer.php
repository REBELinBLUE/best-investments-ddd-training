<?php

namespace App\ValueObjects;

class ManagerIdentifer
{
    private $id;

    public function __construct(int $identifer)
    {
        $this->id = $identifer;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}