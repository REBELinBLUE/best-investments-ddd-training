<?php

namespace BestInvestments\Research\Domain\Entities;

use BestInvestments\Research\Domain\ValueObjects\ConsultationIdentifier;
use BestInvestments\Research\Domain\ValueObjects\ConsultationStatus;
use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use DateTimeImmutable;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use RuntimeException;

class Consultation
{
    /** @var DateTimeImmutable */
    private $startTime;

    /** @var SpecialistIdentifier */
    private $specialistId;

    /** @var ConsultationStatus */
    private $status;

    /** @var int */
    private $duration = 0;

    /** @var ConsultationIdentifier */
    private $consultationId;

    public function __construct(DateTimeImmutable $startTime, SpecialistIdentifier $specialistId)
    {
        $this->startTime      = $startTime;
        $this->specialistId   = $specialistId;
        $this->status         = new ConsultationStatus(ConsultationStatus::OPENED);
        $this->consultationId = new ConsultationIdentifier(Uuid::uuid4()->toString());
    }

    public function discard()
    {
        if (!$this->isOpen()) {
            throw new RuntimeException('The consultation is already closed');
        }

        $this->status = new ConsultationStatus(ConsultationStatus::DISCARDED);
    }

    public function isForSpecialist(SpecialistIdentifier $specialistId)
    {
        return (string) $this->specialistId === (string) $specialistId;
    }

    public function isOpen()
    {
        return $this->status->is(ConsultationStatus::OPENED);
    }

    public function isNotOpen()
    {
        return $this->status->isNot(ConsultationStatus::OPENED);
    }

    public function reportTime(int $duration)
    {
        if ($this->isNotOpen()) {
            throw new RuntimeException('The consultation is already closed');
        }

        if ($duration <= 0) {
            throw new InvalidArgumentException('Reported time can only be a positive integer');
        }

        $this->duration = $duration;
        $this->status   = new ConsultationStatus(ConsultationStatus::CONFIRMED);
    }

    public function getId(): ConsultationIdentifier
    {
        return $this->consultationId;
    }
}
