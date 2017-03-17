<?php

namespace App\Research\Domain\Entities;

use App\Research\Domain\ValueObjects\SpecialistIdentifer;
use App\Research\Domain\ValueObjects\SpecialistStatus;

class Specialist
{
    private $id;
    private $status;
    private $accepted_terms;

    private function __construct(SpecialistIdentifer $identifer)
    {
        $this->id = $identifer;
        $this->status = new SpecialistStatus(SpecialistStatus::PROSPECT);
        $this->accepted_terms = false;
    }

    public static function prospect()
    {
        return new Specialist(new SpecialistIdentifer(1234));
    }

    public function approve()
    {
        if ($this->status->isNot(SpecialistStatus::PROSPECT)) {
            throw new \RuntimeException("Specialist can not be approved if it is not a prospect");
        }

        $this->status = new SpecialistStatus(SpecialistStatus::APPROVED);
    }

    public function discard()
    {
        if ($this->status->isNot(SpecialistStatus::PROSPECT)) {
            throw new \RuntimeException("Specialist can not be discarded if it is not a prospect");
        }

        $this->status = new SpecialistStatus(SpecialistStatus::DISCARDED);
    }

    public function interested()
    {
        if ($this->status->isNot(SpecialistStatus::APPROVED)) {
            throw new \RuntimeException("Specialists can not be interested if they are not approved");
        }

        $this->status = new SpecialistStatus(SpecialistStatus::INTERESTED);
    }

    public function notInterested()
    {
        if ($this->status->isNot(SpecialistStatus::APPROVED)) {
            throw new \RuntimeException("Specialists can not be uninterested if they are not approved");
        }

        $this->status = new SpecialistStatus(SpecialistStatus::NOT_INTERESTED);
    }

    public function getId(): SpecialistIdentifer
    {
        return $this->id;
    }

    public function getStatus(): SpecialistStatus
    {
        return $this->status;
    }

    public function isAcceptedTerms(): bool
    {
        return $this->accepted_terms;
    }
}