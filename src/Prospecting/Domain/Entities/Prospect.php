<?php

namespace App\Prospecting\Domain\Entities;

use App\Prospecting\Domain\ValueObjects\ProspectStatus;

class Prospect
{
    /** @var PotentialSpecialist */
    private $potentialSpecialist;

    /** @var ProspectStatus */
    private $status;

    public function __construct(PotentialSpecialist $potentialSpecialist)
    {
        $this->potentialSpecialist = $potentialSpecialist;
        $this->status              = new ProspectStatus(ProspectStatus::UNKNOWN);
    }

    public function interested()
    {
        if ($this->status->isNot(ProspectStatus::UNKNOWN)) {
            throw new \RuntimeException("Prospects can not be interested if they are not currently unknown");
        }

        $this->status = new ProspectStatus(ProspectStatus::INTERESTED);
    }

    public function notInterested()
    {
        if ($this->status->isNot(ProspectStatus::UNKNOWN)) {
            throw new \RuntimeException("Prospects can not be not interested if they are not currently unknown");
        }

        $this->status = new ProspectStatus(ProspectStatus::NOT_INTERESTED);
    }
}