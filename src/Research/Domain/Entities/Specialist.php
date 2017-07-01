<?php

namespace BestInvestments\Research\Domain\Entities;

use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use BestInvestments\Research\Domain\ValueObjects\SpecialistStatus;

class Specialist
{
    /** @var SpecialistIdentifier */
    private $specialistId;

    /** @var string */
    private $name;

    public function __construct(SpecialistIdentifier $specialistId, string $name)
    {
        $this->specialistId = $specialistId;
        $this->name         = $name;
    }
}
