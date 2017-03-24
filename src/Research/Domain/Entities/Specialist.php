<?php

namespace BestInvestments\Research\Domain\Entities;

use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use BestInvestments\Research\Domain\ValueObjects\SpecialistStatus;

class Specialist
{
    private $identifier;
    private $status;
    private $acceptedTerms;

    private function __construct(SpecialistIdentifier $identifier)
    {
        $this->identifier             = $identifier;
        $this->acceptedTerms          = false;
        $this->status                 = new SpecialistStatus(SpecialistStatus::UNKNOWN);
    }

    public function approve()
    {
        if ($this->status->isNot(SpecialistStatus::UNKNOWN)) {
            throw new \RuntimeException('Specialist can not be discarded if it has already been approved or discarded');
        }

        $this->status = new SpecialistStatus(SpecialistStatus::APPROVED);
    }

    public function discard()
    {
        if ($this->status->isNot(SpecialistStatus::UNKNOWN)) {
            throw new \RuntimeException('Specialist can not be discarded if it has already been approved or discarded');
        }

        $this->status = new SpecialistStatus(SpecialistStatus::DISCARDED);
    }
}
