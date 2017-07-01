<?php

namespace BestInvestments\Research\Domain\ValueObjects;

class ConsultationStatus
{
    const OPENED    = 'opened';
    const DISCARDED = 'disgarded';
    const CONFIRMED = 'confirmed';

    /** @var string */
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
