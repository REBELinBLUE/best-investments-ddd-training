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

    /** @var string */
    private $name;

    /** @var string */
    private $contactDetails;

    public function __construct(ProspectIdentifier $prospectId, string $name, string $contactDetails)
    {
        $this->prospectIdentifier = $prospectId;
        $this->status             = new ProspectStatus(ProspectStatus::NEW);
        $this->name               = $name;
        $this->contactDetails     = $contactDetails;
    }

    public function interested()
    {
        if ($this->status->is(ProspectStatus::REGISTERED)) {
            throw new \RuntimeException('Already registered prospects can not change to interested');
        }

        $this->status = new ProspectStatus(ProspectStatus::INTERESTED);
    }

    public function notInterested()
    {
        if ($this->status->is(ProspectStatus::REGISTERED)) {
            throw new \RuntimeException('Already registered prospects can not change to not interested');
        }

        $this->status = new ProspectStatus(ProspectStatus::NOT_INTERESTED);
    }

    public function register()
    {
        if ($this->status->isNot(ProspectStatus::INTERESTED)) {
            throw new \RuntimeException('Prospects can not register unless they are interested');
        }

        $this->status = new ProspectStatus(ProspectStatus::REGISTERED);

        // TODO: Dispatch ProspectRegisteredEvent
    }
}
