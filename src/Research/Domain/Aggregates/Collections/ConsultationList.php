<?php

namespace BestInvestments\Research\Domain\Aggregates\Collections;

use BestInvestments\Research\Domain\Entities\Consultation;
use BestInvestments\Research\Domain\ValueObjects\ConsultationIdentifier;
use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use Illuminate\Support\Collection;

class ConsultationList
{
    /** @var Collection */
    private $collection;

    public function __construct()
    {
        $this->collection = new Collection();
    }

    public function add(Consultation $consultation)
    {
        return $this->collection->push($consultation);
    }

    public function hasOpenConsultations(): bool
    {
        return $this->collection->filter(function (Consultation $consultation) {
            return $consultation->isOpen();
        })->count() > 0;
    }

    public function getOpenConsultationForSpecialist(SpecialistIdentifier $specialistId): ?Consultation
    {
        return $this->collection->first(function (Consultation $consultation) use ($specialistId) {
            return $consultation->isOpen() && $consultation->isForSpecialist($specialistId);
        });
    }

    public function get(ConsultationIdentifier $consultationId): ?Consultation
    {
        return $this->collection->first(function (Consultation $consultation) use ($consultationId) {
            return $consultation->getId()->is($consultationId);
        });
    }
}
