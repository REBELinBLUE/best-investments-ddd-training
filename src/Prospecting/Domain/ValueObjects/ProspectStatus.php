<?php

namespace App\Prospecting\Domain\ValueObjects;

class ProspectStatus
{
    private $status;

    const UNKNOWN = 'unknown';
    const INTERESTED = 'interested';
    const NOT_INTERESTED = 'not_interested';

    public function __construct(string $status)
    {
        if (!in_array($status, [self::INTERESTED, self::NOT_INTERESTED])) {
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