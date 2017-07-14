<?php

namespace BestInvestments\Invoicing\Domain\ValueObjects;

use InvalidArgumentException;

class TimeIncrement
{
    const MINUTES_PER_INCREMENT = 15;

    /** @var int */
    private $minutes;

    /** @var int */
    private $increments;

    public function __construct(int $minutes)
    {
        if ($minutes < 0) {
            throw new InvalidArgumentException('A time increment must be a positive number');
        }

        $this->minutes    = $minutes;
        $this->increments = (int) ceil($minutes / self::MINUTES_PER_INCREMENT);
    }
}
