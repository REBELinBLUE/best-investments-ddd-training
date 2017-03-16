<?php

namespace App\ValueObjects;

class SpecialistStatus extends ValueObject
{
    const PROSPECT = 'prospect';
    const APPROVED = 'approved';
    const DISCARDED = 'discarded';
    const INTERESTED = 'interested';
    const NOT_INTERESTED = 'not_interested';

    public function __construct(string $status)
    {
        if (!in_array($status, [
            self::PROSPECT, self::INTERESTED, self::NOT_INTERESTED, self::APPROVED, self::DISCARDED
        ])) {
            throw new \RuntimeException("Invalid status {$status}");
        }

        parent::__construct($status);
    }
}