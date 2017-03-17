<?php

namespace App\Research\Domain\ValueObjects;

class SpecialistStatus
{
    private $status;

    //FIXME
    const PROSPECT = 'prospect';
    const APPROVED = 'approved';
    const DISCARDED = 'discarded';
    const INTERESTED = 'interested';
    const NOT_INTERESTED = 'not_interested';

    public function __construct(string $status)
    {
        if (!in_array($status, [
            self::PROSPECT, self::INTERESTED, self::NOT_INTERESTED, self::APPROVED, self::DISCARDED
        ])) {
            throw new \RuntimeException("Invalid status {$status}");
        }

        $this->status = $status;
    }

    public function is($status): bool
    {
        return ($this->status === $status);
    }

    public function isNot($status): bool
    {
        return !$this->is($status);
    }

    public function __toString(): string
    {
        return $this->status;
    }
}