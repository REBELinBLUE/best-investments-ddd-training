<?php

namespace App\ValueObjects;

class ProjectStatus extends ValueObject
{
    const DRAFT = 'draft';
    const STARTED = 'started';

    public function __construct(string $status)
    {
        if (!in_array($status, [self::DRAFT, self::STARTED])) {
            throw new \RuntimeException("Invalid status {$status}");
        }

        parent::__construct($status);
    }
}
