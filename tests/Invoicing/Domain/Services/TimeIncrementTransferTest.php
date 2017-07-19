<?php

namespace BestInvestments\Tests\Invoicing\Domain\Services;

use BestInvestments\Invoicing\Domain\Aggregates\Package;
use BestInvestments\Invoicing\Domain\Repositories\PackageRepositoryInterface;
use BestInvestments\Invoicing\Domain\Services\TimeIncrementTransfer;
use BestInvestments\Invoicing\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageLength;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageReference;
use DateTimeImmutable;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class TimeIncrementTransferTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration; // FIXME: Why is this needed, the listener should take care of it already!

    public function testTransferAvailableTime()
    {
        // Arrange
        $clientId = new ClientIdentifier('client-1234');

        $oldPackageReference = new PackageReference(
            'gold',
            new DateTimeImmutable('2016-07-10'),
            PackageLength::twelveMonths()
        );

        $newPackageReference = new PackageReference(
            'silver',
            new DateTimeImmutable('+1 week'),
            PackageLength::sixMonths()
        );

        $oldPackage = new Package($oldPackageReference, $clientId, 100);
        $newPackage = new Package($newPackageReference, $clientId, 50);

        // Assert
        $repository = Mockery::mock(PackageRepositoryInterface::class);
        $repository->shouldReceive('getByReference')->with($oldPackageReference)->once()->andReturn($oldPackage);
        $repository->shouldReceive('getByReference')->with($newPackageReference)->once()->andReturn($newPackage);

        // Act
        $service = new TimeIncrementTransfer($repository);
        $service->transferAvailableTime($oldPackageReference, $newPackageReference);
    }
}
