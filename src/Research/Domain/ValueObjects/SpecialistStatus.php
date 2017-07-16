<?php

namespace BestInvestments\Research\Domain\ValueObjects;

use InvalidArgumentException;

class SpecialistStatus
{
    const UNKNOWN   = 'unknown';
    const APPROVED  = 'approved';
    const DISCARDED = 'discarded';

    /** @var string */
    private $status;

    public function __construct(string $status)
    {
        if (!in_array($status, [self::UNKNOWN, self::APPROVED, self::DISCARDED], true)) {
            throw new InvalidArgumentException("Invalid status {$status}");
        }

        $this->status = $status;
    }

    public function __toString(): string
    {
        return $this->status;
    }

    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function is($status): bool
    {
        return ($this->status === $status);
    }

    public function isNot($status): bool
    {
        return !$this->is($status);
    }
}
