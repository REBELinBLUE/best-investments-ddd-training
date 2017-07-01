<?php

namespace BestInvestments\Research\Domain\Entities;

use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;

class PotentialSpecialist
{
    /** @var SpecialistIdentifier */
    private $specialistId;

    /** @var string */
    private $name;

    /** @var string */
    private $contactDetails;

    public function __construct(SpecialistIdentifier $specialistId, string $name, string $contactDetails)
    {
        $this->specialistId   = $specialistId;
        $this->name           = $name;
        $this->contactDetails = $contactDetails;

        // TODO: Dispatch NewPotentialSpecialistCreated
    }

    public function register(): Specialist
    {
        return new Specialist($this->specialistId, $this->name);
    }
}
