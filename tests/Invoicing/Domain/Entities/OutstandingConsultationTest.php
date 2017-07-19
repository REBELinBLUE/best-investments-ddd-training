<?php

namespace BestInvestments\Tests\Invoicing\Domain\Entities;

use BestInvestments\Invoicing\Domain\Entities\OutstandingConsultation;
use BestInvestments\Invoicing\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\ConsultationIdentifier;

class OutstandingConsultationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetClientId()
    {
        // Arrange
        $expected     = new ClientIdentifier('client-1234');
        $consultation = new OutstandingConsultation(
            new ConsultationIdentifier('consultation-1234'),
            $expected,
            100
        );

        // Act
        $actual = $consultation->getClientId();

        // Assert
        $this->assertSame($expected, $actual);
    }

    public function testDuration()
    {
        // Arrange
        $expected     = 100;
        $consultation = new OutstandingConsultation(
            new ConsultationIdentifier('consultation-1234'),
            new ClientIdentifier('client-1234'),
            100
        );

        // Act
        $actual = $consultation->getDuration()->getMinutes();

        // Assert
        $this->assertSame($expected, $actual);
    }
}
