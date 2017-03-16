<?php

namespace App\ValueObjects;

class ProjectReference extends ValueObject
{
    public function __construct()
    {
        // TODO: Make random
        parent::__construct('AB1234');
    }
}
