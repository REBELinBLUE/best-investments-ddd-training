<?php

namespace App\ValueObjects;

class ProjectStatus
{
    private $status;

    const DRAFT = 'draft';
    const STARTED = 'started';

    public function __construct(string $status)
    {
        if (!in_array($status, [self::DRAFTED, self::STARTED])) {
            throw new \RuntimeException("Invalid status {$status}");
        }

        $this->status = $status;
    }

    public function __toString(): string
    {
        return $this->status;
    }

    public function is(string $status): bool
    {
        return ($this->status === $status);
    }

    public function isNot(string $status): bool
    {
        return !$this->is($status);
    }
}
