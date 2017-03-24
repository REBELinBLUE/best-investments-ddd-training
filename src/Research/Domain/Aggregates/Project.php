<?php

namespace BestInvestments\Research\Domain\Aggregates;

use BestInvestments\Research\Domain\Entities\Consultation;
use BestInvestments\Research\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Research\Domain\ValueObjects\ManagerIdentifier;
use BestInvestments\Research\Domain\ValueObjects\ProjectReference;
use BestInvestments\Research\Domain\ValueObjects\ProjectStatus;
use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use DateTime;
use Illuminate\Support\Collection;

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

    /** @var Collection */
    private $approvedSpecialists;

    /** @var Collection */
    private $unapprovedSpecialists;

    /** @var Collection */
    private $rejectedSpecialists;

    /** @var Collection */
    private $consultations;

    private function __construct(string $name, DateTime $deadline, ClientIdentifier $clientId)
    {
        $this->name                  = $name;
        $this->deadline              = $deadline;
        $this->reference             = new ProjectReference();
        $this->clientId              = $clientId;
        $this->status                = new ProjectStatus(ProjectStatus::DRAFT);
        $this->approvedSpecialists   = new Collection();
        $this->unapprovedSpecialists = new Collection();
        $this->rejectedSpecialists   = new Collection();
        $this->consultations         = new Collection();

        // TODO: Dispatch NewProjectDrafted event
    }

    public static function draft(string $name, DateTime $deadline, ClientIdentifier $clientId): Project
    {
        return new self($name, $deadline, $clientId);
    }

    public function start(ManagerIdentifier $managerId)
    {
        $this->assertProjectIsStarted();

        $this->managerId = $managerId;
        $this->status    = new ProjectStatus(ProjectStatus::STARTED);
    }

    public function close()
    {
        $this->assertProjectIsStarted();

        // FIXME: Reject all still unapproved specialists?

        $openConsultations = $this->consultations->filter(function (Consultation $consultation) {
            return $consultation->isOpen();
        });

        if ($openConsultations->count() > 0) {
            throw new \RuntimeException('There are consultations still open');
        }

        $this->status = new ProjectStatus(ProjectStatus::CLOSED);
    }

    public function addSpecialist(SpecialistIdentifier $specialistId)
    {
        $this->assertProjectIsStarted();

        if ($this->unapprovedSpecialists->contains($specialistId) ||
            $this->approvedSpecialists->contains($specialistId) ||
            $this->rejectedSpecialists->contains($specialistId)
        ) {
            throw new \RuntimeException('Specialist already added to project.');
        }

        $this->unapprovedSpecialists->push($specialistId);
    }

    public function approveSpecialist(SpecialistIdentifier $specialistId)
    {
        $this->assertProjectIsStarted();

        if ($this->approvedSpecialists->contains($specialistId) ||
            $this->rejectedSpecialists->contains($specialistId)
        ) {
            throw new \RuntimeException('Specialist already approved or rejected');
        }

        if (!$this->unapprovedSpecialists->contains($specialistId)) {
            throw new \RuntimeException('Specialist not added to project');
        }

        $this->unapprovedSpecialists->pull($specialistId);
        $this->approvedSpecialists->pull($specialistId);
    }

    public function rejectSpecialist(SpecialistIdentifier $specialistId)
    {
        $this->assertProjectIsStarted();

        if ($this->approvedSpecialists->contains($specialistId) ||
            $this->rejectedSpecialists->contains($specialistId)
        ) {
            throw new \RuntimeException('Specialist already approved or rejected');
        }

        if (!$this->unapprovedSpecialists->contains($specialistId)) {
            throw new \RuntimeException('Specialist not added to project');
        }

        $this->unapprovedSpecialists->pull($specialistId);
        $this->approvedSpecialists->pull($specialistId);
    }

    public function scheduleConsultation(DateTime $startTime, SpecialistIdentifier $specialistId)
    {
        $this->assertProjectIsStarted();

        // FIXME: Check that a matching consultation doesn't already exist
        // FIXME: Should it allow multiple specialists?
        // FIXME: Log time against consultation?

        $this->consultations->push(new Consultation($startTime, $specialistId));
    }

    public function confirmConsultation(SpecialistIdentifier $specialistId)
    {
        $consultation = $this->consultations->first(function (Consultation $consultation) use ($specialistId) {
            return $consultation->isForSpecialist($specialistId) && $consultation->isOpen();
        });

        if (!$consultation) {
            throw new \RuntimeException('There is no open consultation with the specialist');
        }

        $consultation->close();
    }

    public function discardConsultation(SpecialistIdentifier $specialistId)
    {
        $consultation = $this->consultations->first(function (Consultation $consultation) use ($specialistId) {
            return $consultation->isForSpecialist($specialistId) && $consultation->isOpen();
        });

        if (!$consultation) {
            throw new \RuntimeException('There is no open consultation with the specialist');
        }

        $consultation->close();
    }

    private function assertProjectIsStarted()
    {
        if ($this->status->isNot(ProjectStatus::STARTED)) {
            throw new \RuntimeException('Specialists can not be added when the project is not started.');
        }
    }
}
