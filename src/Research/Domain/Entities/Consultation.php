<?php

namespace BestInvestments\Research\Domain\Entities;

use BestInvestments\Research\Domain\ValueObjects\ConsultationStatus;
use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use DateTime;

class Consultation
{
    /** @var DateTime */
    private $startTime;

    /** @var SpecialistIdentifier */
    private $specialistId;

    /** @var ConsultationStatus */
    private $status;

    public function __construct(DateTime $startTime, SpecialistIdentifier $specialistId)
    {
        $this->startTime    = $startTime;
        $this->specialistId = $specialistId;
        $this->status       = new ConsultationStatus(ConsultationStatus::OPENED);
    }

    public function discard()
    {
        if (!$this->isOpen()) {
            throw new \RuntimeException('The consultation is already closed');
        }

        $this->status = new ConsultationStatus(ConsultationStatus::DISCARDED);
    }

    public function confirm()
    {
        if (!$this->isOpen()) {
            throw new \RuntimeException('The consultation is already closed');
        }

        $this->status = new ConsultationStatus(ConsultationStatus::CONFIRMED);
    }

    public function isForSpecialist(SpecialistIdentifier $specialistId)
    {
        return (string) $this->specialistId === (string) $specialistId;
    }

    public function isOpen()
    {
        return $this->status->is(ConsultationStatus::OPENED);
    }
}
