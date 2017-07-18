<?php

namespace BestInvestments\Invoicing\Domain\Aggregates\Collections;

use BestInvestments\Invoicing\Domain\Entities\OutstandingConsultation;
use Illuminate\Support\Collection;

class ConsultationList
{
    /** @var Collection */
    private $collection;

    public function __construct()
    {
        $this->collection = new Collection();
    }

    public function add(OutstandingConsultation $consultation)
    {
        return $this->collection->push($consultation);
    }

    public function forEach(callable $callback)
    {
        return $this->collection->each($callback);
    }
}
