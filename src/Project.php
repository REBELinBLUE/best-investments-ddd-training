<?php

namespace App;

use App\ValueObjects\ClientIdentifer;
use App\ValueObjects\ManagerIdentifer;
use App\ValueObjects\ProjectReference;
use App\ValueObjects\ProjectStatus;
use DateTime;
use Illuminate\Support\Collection;

class Project
{
    private $name;
    private $deadline;
    private $reference;
    private $clientId;
    private $status;
    private $managerId;

    /**
     * @var Collection
     */
    private $specialists;

    private function __construct(string $name, DateTime $deadline, ClientIdentifer $clientId)
    {
        $this->name      = $name;
        $this->deadline  = $deadline;
        $this->reference = new ProjectReference();
        $this->clientId  = $clientId;
        $this->status    = new ProjectStatus(ProjectStatus::DRAFT);

        // TODO: Dispatch NewProjectDrafted event
    }

    public static function draft(string $name, DateTime $deadline, ClientIdentifer $clientId): Project
    {
        return new Project($name, $deadline, $clientId);
    }

    public function start(ManagerIdentifer $managerId)
    {
        if ($this->status->isNot(ProjectStatus::DRAFT)) {
            throw new \RuntimeException("Project can not be started again.");
        }

        $this->managerId = $managerId;
        $this->status = new ProjectStatus(ProjectStatus::STARTED);
        $this->specialists = new Collection();
    }

    public function addSpecialist(SpecialistIdentifer $specialistId)
    {
        if ($this->status->isNot(ProjectStatus::STARTED)) {
            throw new \RuntimeException("Specialists can not be added when the project is not started.");
        }

        if ($this->specialists->contains($specialistId)) {
            throw new \RuntimeException("Specialist already added to project.");
        }

        $this->specialists->push($specialistId);
    }
}