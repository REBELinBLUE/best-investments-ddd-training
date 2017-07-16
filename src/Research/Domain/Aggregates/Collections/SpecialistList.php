<?php

namespace BestInvestments\Research\Domain\Aggregates\Collections;

use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use Illuminate\Support\Collection;

class SpecialistList
{
    /** @var Collection */
    private $collection;

    public function __construct()
    {
        $this->collection = new Collection();
    }

    public function add(SpecialistIdentifier $specialistId)
    {
        $this->collection->push($specialistId);
    }

    public function remove(SpecialistIdentifier $specialistId)
    {
        $this->collection = $this->collection->filter(function ($entry) use ($specialistId) {
            return $entry !== $specialistId;
        });
    }

    public function contains(SpecialistIdentifier $specialistId): bool
    {
        return $this->collection->contains($specialistId);
    }

    public function doesNotContain(SpecialistIdentifier $specialistId): bool
    {
        return !$this->collection->contains($specialistId);
    }
}
