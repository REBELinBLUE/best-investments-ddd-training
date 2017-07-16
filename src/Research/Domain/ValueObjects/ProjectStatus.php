<?php

namespace BestInvestments\Research\Domain\ValueObjects;

use InvalidArgumentException;

class ProjectStatus
{
    const DRAFT   = 'draft';
    const ACTIVE  = 'active';
    const CLOSED  = 'closed';

    private $status;

    public function __construct(string $status)
    {
        if (!in_array($status, [self::DRAFT, self::ACTIVE, self::CLOSED], true)) {
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
