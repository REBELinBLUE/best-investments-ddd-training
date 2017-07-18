<?php

namespace BestInvestments\Invoicing\Domain\Aggregates;

use BestInvestments\Invoicing\Domain\Aggregates\Collections\ConsultationList;
use BestInvestments\Invoicing\Domain\Entities\OutstandingConsultation;
use BestInvestments\Invoicing\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageReference;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageStatus;
use BestInvestments\Invoicing\Domain\ValueObjects\TimeIncrement;
use BestInvestments\Invoicing\Domain\ValueObjects\TimeTransfer;
use InvalidArgumentException;
use RuntimeException;

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

    /** @var ConsultationList */
    private $consultations = [];

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    public function __construct(PackageReference $reference, ClientIdentifier $clientId, int $nominalHours)
    {
        if ($nominalHours <= 0) {
            throw new InvalidArgumentException('A package can not have a nominal hours less than or equal to 0');
        }

        $this->reference           = $reference;
        $this->clientId            = $clientId;
        $this->nominalHours        = $nominalHours;
        $this->availableHours      = new TimeIncrement($nominalHours * 60);
        $this->transferredOutHours = new TimeIncrement(0);
        $this->consultations       = new ConsultationList();
        $this->status              = PackageStatus::determine($reference->getStartDate(), $reference->getLength());
    }

    public function attachConsultation(OutstandingConsultation $consultation)
    {
        $this->ensurePackageIsStarted();

        if ($consultation->getClientId()->isNot($this->clientId)) {
            throw new RuntimeException('The consultation does not belong to the same client as the package');
        }

        if ($consultation->getDuration()->isMoreThan($this->getRemainingHours())) {
            throw new RuntimeException('The consultation has a longer duration than is remaining on the package');
        }

        $this->consultations->add($consultation);
    }

    public function transferRemainingHoursOut(): TimeTransfer
    {
        if ($this->status->isNot(PackageStatus::EXPIRED)) {
            throw new RuntimeException('You can not transfer time from a package which has not expired');
        }

        $this->transferredOutHours = $this->getRemainingHours();

        return new TimeTransfer($this->transferredOutHours, $this->clientId);
    }

    public function transferHoursIn(TimeTransfer $transfer)
    {
        if ($this->status->isNot(PackageStatus::NOT_STARTED)) {
            throw new RuntimeException('You can not transfer time into a package which has already started');
        }

        if ($transfer->isForClient($this->clientId)) {
            throw new RuntimeException('You can not transfer time into a package for a different client');
        }

        $this->availableHours = $this->availableHours->add($transfer->getTime());
    }

    private function ensurePackageIsStarted()
    {
        if ($this->status->isNot(PackageStatus::STARTED)) {
            throw new RuntimeException('The package is not currently active');
        }
    }

    private function getRemainingHours(): TimeIncrement
    {
        $used = new TimeIncrement(0);

        $this->consultations->forEach(function (OutstandingConsultation $consultation) use (&$used) {
            $used = $used->add($consultation->getDuration());
        });

        return $this->availableHours->subtract($used->add($this->transferredOutHours));
    }
}
