<?php

namespace BestInvestments\Invoicing\Domain\ValueObjects;

class TimeTransfer
{
    /** @var TimeIncrement */
    private $time;

    /** @var ClientIdentifier */
    private $clientId;

    public function __construct(TimeIncrement $time, ClientIdentifier $clientId)
    {
        $this->time     = $time;
        $this->clientId = $clientId;
    }

    public function isForClient(ClientIdentifier $clientId)
    {
        return $this->clientId->is($clientId);
    }

    public function getTime(): TimeIncrement
    {
        return $this->time;
    }
}
