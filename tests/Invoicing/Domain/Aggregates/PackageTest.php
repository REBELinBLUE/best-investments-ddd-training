<?php

namespace BestInvestments\Tests\Invoicing\Domain\Aggregates;

use BestInvestments\Invoicing\Domain\Aggregates\Collections\ConsultationList;
use BestInvestments\Invoicing\Domain\Aggregates\Package;
use BestInvestments\Invoicing\Domain\Entities\OutstandingConsultation;
use BestInvestments\Invoicing\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\ConsultationIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageLength;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageReference;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageStatus;
use BestInvestments\Invoicing\Domain\ValueObjects\TimeIncrement;
use BestInvestments\Invoicing\Domain\ValueObjects\TimeTransfer;
use BestInvestments\Tests\PrivatePropertyTrait;
use DateTimeImmutable;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use RuntimeException;

class PackageTest extends \PHPUnit_Framework_TestCase
{
    use PrivatePropertyTrait;

    /**
     * @dataProvider providePackageDate
     */
    public function testInCanBeConstructed($status, $date)
    {
        // Arrange
        $reference = new PackageReference('gold', new DateTimeImmutable($date), PackageLength::sixMonths());
        $clientId  = new ClientIdentifier('client-1234');

        // Act
        $package   = new Package($reference, $clientId, 100);

        // Assert
        /** @var ConsultationList $consultations */
        $consultations = $this->getInnerPropertyValueByReflection($package, 'consultations');

        $this->assertInstanceOf(ConsultationList::class, $consultations);

        /** @var TimeIncrement $availableHours */
        $availableHours = $this->getInnerPropertyValueByReflection($package, 'availableHours');

        $this->assertInstanceOf(TimeIncrement::class, $availableHours);
        $this->assertSame(6000, $availableHours->getMinutes());

        /** @var TimeIncrement $transferredOutHours */
        $transferredOutHours = $this->getInnerPropertyValueByReflection($package, 'transferredOutHours');

        $this->assertInstanceOf(TimeIncrement::class, $transferredOutHours);
        $this->assertSame(0, $transferredOutHours->getMinutes());

        /** @var PackageStatus $packageStatus */
        $packageStatus = $this->getInnerPropertyValueByReflection($package, 'status');

        $this->assertInstanceOf(PackageStatus::class, $packageStatus);
        $this->assertTrue($packageStatus->is($status), 'Status should be not started');
    }

    public function providePackageDate()
    {
        return [
            [PackageStatus::NOT_STARTED, 'tomorrow'],
            [PackageStatus::STARTED, 'yesterday'],
            [PackageStatus::EXPIRED, '-2 years'],
        ];
    }

    public function testItThrowsAnExceptionWhenNominalHoursIsZero()
    {
        // Arrange
        $reference = new PackageReference('gold', new DateTimeImmutable('tomorrow'), PackageLength::sixMonths());
        $clientId  = new ClientIdentifier('client-1234');

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        new Package($reference, $clientId, 0);
    }

    public function testItThrowsAnExceptionWhenNominalHoursIsNegative()
    {
        // Arrange
        $reference = new PackageReference('gold', new DateTimeImmutable('tomorrow'), PackageLength::sixMonths());
        $clientId  = new ClientIdentifier('client-1234');

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        new Package($reference, $clientId, -150);
    }

    public function testAttachConsultation()
    {
        // Arrange
        $reference = new PackageReference('gold', new DateTimeImmutable('2017-05-10'), PackageLength::sixMonths());
        $clientId  = new ClientIdentifier('client-1234');
        $package   = new Package($reference, $clientId, 100);

        // Act
        $firstConsultation = new OutstandingConsultation(
            new ConsultationIdentifier('consultation-1234'),
            $clientId,
            45
        );

        $secondConsultation = new OutstandingConsultation(
            new ConsultationIdentifier('consultation-9876'),
            $clientId,
            45
        );

        $package->attachConsultation($firstConsultation);
        $package->attachConsultation($secondConsultation);

        // Assert
        /** @var Collection $collection */
        $collection = $this->getInnerPropertyValueByReflection(
            $this->getInnerPropertyValueByReflection($package, 'consultations'),
            'collection'
        );

        $this->assertSame(2, $collection->count());
        $this->assertSame($collection->pop(), $secondConsultation);
        $this->assertSame($collection->pop(), $firstConsultation);
    }

    public function testAttachConsultationThrowsExceptionIfNotStarted()
    {
        // Arrange
        $reference = new PackageReference('gold', new DateTimeImmutable('tomorrow'), PackageLength::sixMonths());
        $clientId  = new ClientIdentifier('client-1234');
        $package   = new Package($reference, $clientId, 100);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $package->attachConsultation(new OutstandingConsultation(
            new ConsultationIdentifier('consultation-1234'),
            $clientId,
            45
        ));
    }

    public function testAttachConsultationThrowsExceptionIfNotSameClient()
    {
        // Arrange
        $reference = new PackageReference('gold', new DateTimeImmutable('yesterday'), PackageLength::sixMonths());
        $package   = new Package($reference, new ClientIdentifier('client-1234'), 100);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $package->attachConsultation(new OutstandingConsultation(
            new ConsultationIdentifier('consultation-1234'),
            new ClientIdentifier('client-9876'),
            45
        ));
    }

    public function testTransferRemainingHoursOut()
    {
        // Arrange
        $reference = new PackageReference('gold', new DateTimeImmutable('2015-05-10'), PackageLength::sixMonths());
        $clientId  = new ClientIdentifier('client-1234');
        $package   = new Package($reference, $clientId, 100);

        // Act
        $result = $package->transferRemainingHoursOut();

        // Assert
        $this->assertSame(6000, $result->getTime()->getMinutes());
        $this->assertTrue($result->isForClient($clientId), 'Time transfer not for expected client');
    }

    public function testTransferRemainingHoursOutCalculatesTimeCorrectly()
    {
        $this->markTestSkipped(
            'Can not test this as the package needs to be STARTED to add consultations but EXPIRED to transfer time.'
        );

        // Arrange
        $reference = new PackageReference('gold', new DateTimeImmutable('2017-05-10'), PackageLength::sixMonths());
        $clientId  = new ClientIdentifier('client-1234');
        $package   = new Package($reference, $clientId, 100);

        $package->attachConsultation(new OutstandingConsultation(
            new ConsultationIdentifier('consultation-1234'),
            $clientId,
            45
        ));

        $package->attachConsultation(new OutstandingConsultation(
            new ConsultationIdentifier('consultation-7629'),
            $clientId,
            140
        ));

        // Act
        $result = $package->transferRemainingHoursOut();

        // Assert
        $this->assertSame(6000, $result->getTime()->getMinutes());
        $this->assertTrue($result->isForClient($clientId), 'Time transfer not for expected client');
    }

    public function testTransferRemainingHoursOutThrowsExceptionWhenNotExpired()
    {
        // Arrange
        $reference = new PackageReference('gold', new DateTimeImmutable('yesterday'), PackageLength::sixMonths());
        $clientId  = new ClientIdentifier('client-1234');
        $package   = new Package($reference, $clientId, 100);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $package->transferRemainingHoursOut();
    }

    public function testTransferHoursIn()
    {
        // Arrange
        $clientId  = new ClientIdentifier('client-1234');
        $reference = new PackageReference('gold', new DateTimeImmutable('tomorrow'), PackageLength::sixMonths());
        $package   = new Package($reference, $clientId, 100);

        $transfer  = new TimeTransfer(new TimeIncrement(65), $clientId);

        // Act
        $package->transferHoursIn($transfer);

        // Assert
        /** @var TimeIncrement $availableHours */
        $availableHours = $this->getInnerPropertyValueByReflection($package, 'availableHours');

        /** @var int $increments */
        $increments = $this->getInnerPropertyValueByReflection($availableHours, 'increments');

        $this->assertSame(6065, $availableHours->getMinutes());
        $this->assertSame(405, $increments);
    }

    public function testTransferHoursInThrowsExceptionWhenPackageHasStarted()
    {
        // Arrange
        $clientId  = new ClientIdentifier('client-1234');
        $reference = new PackageReference('gold', new DateTimeImmutable('yesterday'), PackageLength::sixMonths());
        $package   = new Package($reference, $clientId, 100);

        $transfer  = new TimeTransfer(new TimeIncrement(65), $clientId);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $package->transferHoursIn($transfer);
    }

    public function testTransferHoursInThrowsExceptionWhenPackageHasDifferentClient()
    {
        // Arrange
        $reference = new PackageReference('gold', new DateTimeImmutable('tomorrow'), PackageLength::sixMonths());
        $package   = new Package($reference, new ClientIdentifier('client-1234'), 100);

        $transfer  = new TimeTransfer(new TimeIncrement(65), new ClientIdentifier('client-9876'));

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $package->transferHoursIn($transfer);
    }
}
