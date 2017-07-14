<?php

namespace BestInvestments\Invoicing\Domain\Aggregates;

use BestInvestments\Invoicing\Domain\Entities\OutstandingConsultation;
use BestInvestments\Invoicing\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageReference;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageStatus;
use BestInvestments\Invoicing\Domain\ValueObjects\TimeIncrement;

class Package
{
    /** @var PackageReference */
    private $reference;

    /** @var ClientIdentifier */
    private $clientId;

    /** @var int */
    private $nominalHours;

    /** @var PackageStatus */
    private $status;

    /** @var TimeIncrement */
    private $availableHours;

    /** @var TimeIncrement */
    private $transferredOutHours;

    /** @var Consultation[] */
    private $consultations = [];

    public function __construct(PackageReference $reference, ClientIdentifier $clientId, int $nominalHours)
    {
        if ($nominalHours <= 0) {
            throw new \InvalidArgumentException('A package can not have a nominal hours less than or equal to 0');
        }

        $this->reference           = $reference;
        $this->clientId            = $clientId;
        $this->nominalHours        = $nominalHours;
        $this->availableHours      = new TimeIncrement($nominalHours * 60);
        $this->transferredOutHours = new TimeIncrement(0);
        $this->status              = new PackageStatus(PackageStatus::NOT_STARTED);
    }

    public function attachConsultation(OutstandingConsultation $consultation)
    {
        $this->consultations[] = $consultation;
    }

    public function transferRemainingHoursOut(): TimeIncrement
    {
    }

    public function transferHoursIn(TimeIncrement $transfer)
    {
    }
}
