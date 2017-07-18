<?php

namespace BestInvestments\Tests\Research\Domain\ValueObjects;

use BestInvestments\Research\Domain\ValueObjects\ConsultationIdentifier;

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

    public function testIsReturnsTrueWhenConsultationIdMatches()
    {
        // Arrange
        $consultationId = new ConsultationIdentifier('consultation-1234');

        // Act
        $result = $consultationId->is(new ConsultationIdentifier('consultation-1234'));

        // Assert
        $this->assertTrue($result);
    }

    public function testIsReturnsFlaseWhenConsultationIdDoesNotMatch()
    {
        // Arrange
        $consultationId = new ConsultationIdentifier('consultation-1234');

        // Act
        $result = $consultationId->is(new ConsultationIdentifier('consultation-9876'));

        // Assert
        $this->assertFalse($result);
    }
}
