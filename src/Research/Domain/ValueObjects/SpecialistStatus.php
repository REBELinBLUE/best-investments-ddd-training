<?php

namespace BestInvestments\Research\Domain\ValueObjects;

class SpecialistStatus
{
    const UNKNOWN   = 'unknown';
    const APPROVED  = 'approved';
    const DISCARDED = 'discarded';
    private $status;

    public function __construct(string $status)
    {
        if (!in_array($status, [self::UNKNOWN, self::APPROVED, self::DISCARDED], true)) {
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
