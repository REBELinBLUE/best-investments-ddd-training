<?php

namespace App\ValueObjects;

class ProjectReference
{
    private $reference;

    public function __construct()
    {
        $this->reference = 'AB1234'; // TODO: Make random
    }

    public function __toString(): string
    {
        return $this->reference;
    }
}
