<?php

namespace BestInvestments\Invoicing\Domain\ValueObjects;

class ClientIdentifier
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /** @SuppressWarnings(PHPMD.ShortMethodName) */
    public function is(ClientIdentifier $clientId)
    {
        return $this->value === (string) $clientId;
    }

    public function isNot(ClientIdentifier $clientId)
    {
        return $this->value !== (string) $clientId;
    }
}
