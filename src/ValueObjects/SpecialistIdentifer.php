<?php

namespace App\ValueObjects;

class SpecialistIdentifer
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