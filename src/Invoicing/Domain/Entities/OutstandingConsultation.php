<?php

namespace BestInvestments\Invoicing\Domain\Entities;

use BestInvestments\Invoicing\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\ConsultationIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\TimeIncrement;

class OutstandingConsultation
{
    /** @var ConsultationIdentifier */
    private $consultationId;

    /** @var ClientIdentifier */
    private $clientId;

    /** @var TimeIncrement */
    private $duration;

    public function __construct(ConsultationIdentifier $consultationId, ClientIdentifier $clientId, int $duration)
    {
        $this->consultationId = $consultationId;
        $this->clientId       = $clientId;
        $this->duration       = new TimeIncrement($duration);
    }

    public function getClientId(): ClientIdentifier
    {
        return $this->clientId;
    }

    public function getDuration(): TimeIncrement
    {
        return $this->duration;
    }
}
