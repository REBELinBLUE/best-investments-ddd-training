<?php

namespace BestInvestments\Tests\Invoicing\Domain\ValueObjects;

use BestInvestments\Invoicing\Domain\ValueObjects\ConsultationIdentifier;

class ConsultationIdentifierTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringReturnsValue()
    {
        // Arrange
        $expected       = 'consultation-1234';
        $consultationId = new ConsultationIdentifier($expected);

        // Act
        $actual = (string) $consultationId;

        // Assert
        $this->assertSame($expected, $actual);
    }
}
