<?php

namespace BestInvestments\Invoicing\Domain\ValueObjects;

use DateTimeImmutable;

class PackageReference
{
    /** @var string */
    private $name;

    /** @var DateTimeImmutable */
    private $startDate;

    /** @var PackageLength */
    private $length;

    public function __construct(string $name, DateTimeImmutable $startDate, PackageLength $length)
    {
        $this->name      = $name;
        $this->startDate = $startDate;
        $this->length    = $length;
    }

    public function __toString(): string
    {
        return sprintf('%s-%s-%s', $this->name, $this->startDate->format('Ym'), $this->length);
    }
}
