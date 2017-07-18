<?php

namespace BestInvestments\Invoicing\Domain\ValueObjects;

use DateInterval;
use DateTimeImmutable;
use RuntimeException;

class PackageStatus
{
    const STARTED     = 'started';
    const EXPIRED     = 'expired';
    const NOT_STARTED = 'not started';

    /** @var string */
    private $status;

    public function __construct(string $status)
    {
        if (!in_array($status, [self::STARTED, self::EXPIRED, self::NOT_STARTED], true)) {
            throw new RuntimeException("Invalid status {$status}");
        }

        $this->status = $status;
    }

    public function __toString(): string
    {
        return $this->status;
    }

    public static function determine(DateTimeImmutable $startDate, PackageLength $length)
    {
        $currentDate = new DateTimeImmutable();

        if ($currentDate < $startDate) {
            return new self(self::NOT_STARTED);
        }

        $endDate = $startDate->add(new DateInterval(sprintf('P%sM', $length)));

        if ($currentDate > $endDate) {
            return new self(self::EXPIRED);
        }

        return new self(self::STARTED);
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
