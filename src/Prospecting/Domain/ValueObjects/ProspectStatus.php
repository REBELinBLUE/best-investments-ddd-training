<?php

namespace BestInvestments\Prospecting\Domain\ValueObjects;

use InvalidArgumentException;

class ProspectStatus
{
    const NEW            = 'new';
    const INTERESTED     = 'interested';
    const NOT_INTERESTED = 'not_interested';
    const REGISTERED     = 'registered';

    /** @var string */
    private $status;

    public function __construct(string $status)
    {
        if (!in_array($status, [self::NEW, self::INTERESTED, self::NOT_INTERESTED, self::REGISTERED], true)) {
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
