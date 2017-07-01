<?php

namespace BestInvestments\Research\Support;

use BestInvestments\Research\Domain\Entities\Consultation;
use Illuminate\Support\Collection;

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
}
