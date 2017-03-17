<?php

namespace App\Research\Domain\ValueObjects;

class ProjectStatus
{
    private $status;

    const DRAFT = 'draft';
    const STARTED = 'started';

    public function __construct(string $status)
    {
        if (!in_array($status, [self::DRAFT, self::STARTED])) {
            throw new \RuntimeException("Invalid status {$status}");
        }

        $this->status = $status;
    }

    public function is($status): bool
    {
        return ($this->status === $status);
    }

    public function isNot($status): bool
    {
        return !$this->is($status);
    }

    public function __toString(): string
    {
        return $this->status;
    }
}
