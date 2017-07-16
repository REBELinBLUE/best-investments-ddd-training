<?php

namespace BestInvestments\Research\Domain\Aggregates\Collections;

use BestInvestments\Research\Domain\Entities\Consultation;
use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use Illuminate\Support\Collection;

// FIXME: Change this to create a collection not extend it
class ConsultationList extends Collection
{
    public function add(Consultation $consultation): self
    {
        return $this->push($consultation);
    }

    public function hasOpenConsultations(): bool
    {
        return $this->filter(function (Consultation $consultation) {
            return $consultation->isOpen();
        })->count() > 0;
    }

    public function getOpenConsultationForSpecialist(SpecialistIdentifier $specialistId): ?Consultation
    {
        return $this->first(function (Consultation $consultation) use ($specialistId) {
            return $consultation->isOpen() && $consultation->isForSpecialist($specialistId);
        });
    }
}
