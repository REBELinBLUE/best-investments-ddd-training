<?php

namespace BestInvestments\Prospecting\Domain\Entities;

use BestInvestments\Prospecting\Domain\ValueObjects\ProspectIdentifier;
use BestInvestments\Prospecting\Domain\ValueObjects\ProspectStatus;

class Prospect
{
    /** @var ProspectIdentifier */
    private $prospectIdentifier;

    /** @var ProspectStatus */
    private $status;

    public function __construct(ProspectIdentifier $prospectIdentifier)
    {
        $this->prospectIdentifier = $prospectIdentifier;
        $this->status             = new ProspectStatus(ProspectStatus::UNKNOWN);
    }

    public function interested()
    {
        if ($this->status->isNot(ProspectStatus::UNKNOWN)) {
            throw new \RuntimeException('Prospects can not be interested if they are not currently unknown');
        }

        $this->status = new ProspectStatus(ProspectStatus::INTERESTED);
    }

    public function notInterested()
    {
        if ($this->status->isNot(ProspectStatus::UNKNOWN)) {
            throw new \RuntimeException('Prospects can not be not interested if they are not currently unknown');
        }

        $this->status = new ProspectStatus(ProspectStatus::NOT_INTERESTED);
    }
}
