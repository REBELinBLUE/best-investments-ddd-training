<?php

namespace BestInvestments\Tests\Invoicing\Domain\Aggregates\Collections;

use BestInvestments\Invoicing\Domain\Aggregates\Collections\ConsultationList;
use BestInvestments\Invoicing\Domain\Entities\OutstandingConsultation;
use BestInvestments\Invoicing\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\ConsultationIdentifier;
use BestInvestments\Tests\PrivatePropertyTrait;

class ConsultationListTest extends \PHPUnit_Framework_TestCase
{
    use PrivatePropertyTrait;

    public function testAddPushesConsultationToCollection()
    {
        // Arrange
        $consultationList   = new ConsultationList();
        $consultation       = $this->getConsultation();

        // Act
        $consultationList->add($consultation);

        // Assert
        /** @var Collection $collection */
        $collection = $this->getInnerPropertyValueByReflection($consultationList, 'collection');

        $this->assertSame(1, $collection->count());
        $this->assertSame($collection->pop(), $consultation);
    }

    public function testForEach()
    {
        // Arrange
        $expected = $this->getConsultation();

        $consultationList = new ConsultationList();
        $consultationList->add($expected);

        // Act
        $counter = 0;
        $consultationList->forEach(function (OutstandingConsultation $actual) use (&$counter, $expected) {
            $this->assertSame($actual, $expected);
            $counter++;
        });

        // Assert
        /** @var Collection $collection */
        $collection = $this->getInnerPropertyValueByReflection($consultationList, 'collection');
        $actual     = $collection->count();

        $this->assertSame($actual, $counter, "Callback was expected to be called {$actual} times, called {$counter}");
    }

    private function getConsultation(): OutstandingConsultation
    {
        return new OutstandingConsultation(
            new ConsultationIdentifier('consultation-1234'),
            new ClientIdentifier('client-1234'),
            100
        );
    }
}
