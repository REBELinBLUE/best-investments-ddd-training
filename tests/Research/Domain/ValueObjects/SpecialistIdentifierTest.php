<?php

namespace BestInvestments\Tests\Research\Domain\ValueObjects;

use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;

class SpecialistIdentifierTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringReturnsValue()
    {
        // Arrange
        $expected     = 'specialist-1234';
        $specialistId = new SpecialistIdentifier($expected);

        // Act
        $actual = (string) $specialistId;

        // Assert
        $this->assertSame($expected, $actual);
    }
}
