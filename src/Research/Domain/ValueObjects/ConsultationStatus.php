<?php

namespace BestInvestments\Research\Domain\ValueObjects;

use InvalidArgumentException;

class ConsultationStatus
{
    const OPENED    = 'opened';
    const DISCARDED = 'discarded';
    const CONFIRMED = 'confirmed';

    /** @var string */
    private $status;

    public function __construct(string $status)
    {
        if (!in_array($status, [self::OPENED, self::DISCARDED, self::CONFIRMED], true)) {
            throw new InvalidArgumentException("Invalid status {$status}");
        }

        $this->status = $status;
    }

    public function __toString(): string
    {
        return $this->status;
    }

    /** @SuppressWarnings(PHPMD.ShortMethodName) */
    public function is($status): bool
    {
        return ($this->status === $status);
    }

    public function isNot($status): bool
    {
        return !$this->is($status);
    }
}
