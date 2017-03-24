<?php

namespace BestInvestments\Research\Domain\ValueObjects;

class ConsultationStatus
{
    const OPENED    = 'opened';
    const DISCARDED = 'disgarded';
    const CONFIRMED = 'confirmed';

    private $status;

    public function __construct(string $status)
    {
        if (!in_array($status, [self::OPENED, self::DISCARDED, self::CONFIRMED], true)) {
            throw new \RuntimeException("Invalid status {$status}");
        }

        $this->status = $status;
    }

    public function __toString(): string
    {
        return $this->status;
    }

    public function is($status): bool
    {
        return ($this->status === $status);
    }

    public function isNot($status): bool
    {
        return !$this->is($status);
    }
}
