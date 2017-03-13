<?php

namespace App\ValueObjects;

class ClientIdentifer
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