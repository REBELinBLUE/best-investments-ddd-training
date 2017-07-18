<?php

namespace BestInvestments\Invoicing\Domain\Services;

use BestInvestments\Invoicing\Domain\Repositories\PackageRepositoryInterface;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageReference;

class TimeIncrementTransfer
{
    /** @var PackageRepositoryInterface */
    private $repository;

    public function __construct(PackageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function transferAvailableTime(PackageReference $oldPackageReference, PackageReference $newPackageReference)
    {
        $oldPackage = $this->repository->getByReference($oldPackageReference);
        $newPackage = $this->repository->getByReference($newPackageReference);

        $transfer = $oldPackage->transferRemainingHoursOut();
        $newPackage->transferHoursIn($transfer);
    }
}
