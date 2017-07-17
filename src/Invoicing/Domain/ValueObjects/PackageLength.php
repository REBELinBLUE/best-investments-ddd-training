<?php

namespace BestInvestments\Invoicing\Domain\ValueObjects;

use InvalidArgumentException;

class PackageLength
{
    /** @var int */
    private $length;

    public function __construct(int $length) // FIXME: Maybe make this private
    {
        if ($length !== 6 && $length !== 12) {
            throw new InvalidArgumentException('The package length must be 6 or 12 months');
        }

        $this->length = $length;
    }

    public function __toString(): string
    {
        return (string) $this->length;
    }

    public static function sixMonths(): PackageLength
    {
        return new self(6);
    }

    public static function twelveMonths(): PackageLength
    {
        return new self(12);
    }
}
