<?php

namespace BestInvestments\Research\Domain\Aggregates;

use BestInvestments\Research\Domain\Entities\Consultation;
use BestInvestments\Research\Domain\Entities\PotentialSpecialist;
use BestInvestments\Research\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Research\Domain\ValueObjects\ConsultationIdentifier;
use BestInvestments\Research\Domain\ValueObjects\ManagerIdentifier;
use BestInvestments\Research\Domain\ValueObjects\ProjectReference;
use BestInvestments\Research\Domain\ValueObjects\ProjectStatus;
use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use DateTime;
use BestInvestments\Research\Support\ConsultationList;
use BestInvestments\Research\Support\SpecialistList;

class Project
{
    /** @var string */
    private $name;

    /** @var DateTime */
    private $deadline;

    /** @var ProjectReference */
    private $reference;

    /** @var ClientIdentifier */
    private $clientId;

    /** @var ProjectStatus */
    private $status;

    /** @var ManagerIdentifier */
    private $managerId;

    /** @var SpecialistList */
    private $approvedSpecialists;

    /** @var SpecialistList */
    private $unapprovedSpecialists;

    /** @var SpecialistList */
    private $discardedSpecialists;

    /** @var ConsultationList */
    private $consultations;

    private function __construct(string $name, DateTime $deadline, ClientIdentifier $clientId)
    {
        $this->name                  = $name;
        $this->deadline              = $deadline;
        $this->reference             = new ProjectReference();
        $this->clientId              = $clientId;
        $this->status                = new ProjectStatus(ProjectStatus::DRAFT);
        $this->approvedSpecialists   = new SpecialistList();
        $this->unapprovedSpecialists = new SpecialistList();
        $this->discardedSpecialists  = new SpecialistList();
        $this->consultations         = new ConsultationList();

        // TODO: Dispatch NewProjectDrafted event
    }

    public static function draft(string $name, DateTime $deadline, ClientIdentifier $clientId): Project
    {
        return new self($name, $deadline, $clientId);
    }

    public function start(ManagerIdentifier $managerId)
    {
        if ($this->status->isNot(ProjectStatus::DRAFT)) {
            throw new \RuntimeException('The project is already started');
        }

        $this->managerId = $managerId;
        $this->status    = new ProjectStatus(ProjectStatus::ACTIVE);
    }

    public function close()
    {
        $this->ensureProjectIsActive();

        if ($this->consultations->hasOpenConsultations()) {
            throw new \RuntimeException('There are consultations still open');
        }

        $this->status = new ProjectStatus(ProjectStatus::CLOSED);
    }

    // FIXME: Not sure this is right?
    public function potentialSpecialist(string $name, string $contactDetails)
    {
        $this->ensureProjectIsActive();

        new PotentialSpecialist(new SpecialistIdentifier(1234), $name, $contactDetails);

        // TODO: Dispatch NewPotentialSpecialCreatedEvent
    }

    public function addSpecialist(SpecialistIdentifier $specialistId)
    {
        $this->ensureProjectIsActive();

        if ($this->unapprovedSpecialists->contains($specialistId) ||
            $this->approvedSpecialists->contains($specialistId) ||
            $this->discardedSpecialists->contains($specialistId)
        ) {
            throw new \RuntimeException('Specialist already added to project.');
        }

        $this->unapprovedSpecialists->add($specialistId);
    }

    public function approveSpecialist(SpecialistIdentifier $specialistId)
    {
        $this->ensureProjectIsActive();

        if ($this->unapprovedSpecialists->doesNotContain($specialistId)) {
            throw new \RuntimeException('Specialist not added to project');
        }

        $this->unapprovedSpecialists->remove($specialistId);
        $this->approvedSpecialists->add($specialistId);
    }

    public function discardSpecialist(SpecialistIdentifier $specialistId)
    {
        $this->ensureProjectIsActive();

        if ($this->unapprovedSpecialists->doesNotContain($specialistId)) {
            throw new \RuntimeException('Specialist not added to project');
        }

        $this->unapprovedSpecialists->remove($specialistId);
        $this->discardedSpecialists->add($specialistId);
    }

    public function scheduleConsultation(
        DateTime $startTime,
        SpecialistIdentifier $specialistId
    ): ConsultationIdentifier {
        $this->ensureProjectIsActive();

        if ($this->approvedSpecialists->doesNotContain($specialistId)) {
            throw new \RuntimeException('The specialist is not currently approved');
        }

        $consultation = $this->getOpenConsultationForSpecialist($specialistId);
        if ($consultation) {
            throw new \RuntimeException('There is already an open consultation with the specialist');
        }

        $consultation = new Consultation($startTime, $specialistId);

        $this->consultations->add($consultation);

        return $consultation->getId();
    }

    public function reportConsultation(ConsultationIdentifier $consultationId, int $duration)
    {
        if ($this->consultations->contains($consultationId)) {
            throw new \RuntimeException('There is no consultation with the supplied id');
        }

        $consultation = $this->consultations->get($consultationId);

        $consultation->reportTime($duration);
    }

    public function discardConsultation(ConsultationIdentifier $consultationId)
    {
        if ($this->consultations->contains($consultationId)) {
            throw new \RuntimeException('There is no consultation with the supplied id');
        }

        $consultation = $this->consultations->get($consultationId);

        if ($consultation->isNotOpen()) {
            throw new \RuntimeException('The consultation is not open');
        }

        $consultation->discard();
    }

    private function getOpenConsultationForSpecialist(SpecialistIdentifier $specialistId): ?Consultation
    {
        return null;
    }

    private function ensureProjectIsActive()
    {
        if ($this->status->isNot(ProjectStatus::ACTIVE)) {
            throw new \RuntimeException('The project is not yet started');
        }
    }
}
