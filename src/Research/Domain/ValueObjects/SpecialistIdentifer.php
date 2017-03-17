<?php

namespace App\Research\Domain\ValueObjects;

class SpecialistIdentifer
{
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}
