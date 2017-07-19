<?php

namespace BestInvestments\Tests\Invoicing\Domain\Aggregates;

use BestInvestments\Invoicing\Domain\Aggregates\Package;
use BestInvestments\Invoicing\Domain\Entities\OutstandingConsultation;
use BestInvestments\Invoicing\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\ConsultationIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageLength;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageReference;
use BestInvestments\Tests\PrivatePropertyTrait;
use Illuminate\Support\Collection;

class PackageTest extends \PHPUnit_Framework_TestCase
{
    use PrivatePropertyTrait;

    public function testAttachConsultation()
    {
        // Arrange
        $reference = new PackageReference('gold', new \DateTimeImmutable('2017-05-10'), PackageLength::sixMonths());
        $clientId  = new ClientIdentifier('client-1234');
        $package   = new Package($reference, $clientId, 100);

        // Act
        $consultation = new OutstandingConsultation(
            new ConsultationIdentifier('consultation-1234'),
            $clientId,
            45
        );

        $package->attachConsultation($consultation);

        // Assert
        /** @var ConsultationList $consultation */
        $collection = $this->getInnerPropertyValueByReflection(
            $this->getInnerPropertyValueByReflection($package, 'consultations'),
            'collection'
        );

        $this->assertSame(1, $collection->count());
        $this->assertSame($collection->pop(), $consultation);
    }
}
