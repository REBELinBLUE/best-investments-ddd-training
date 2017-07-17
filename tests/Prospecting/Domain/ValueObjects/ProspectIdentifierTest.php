<?php

namespace BestInvestments\Tests\Prospecting\Domain\ValueObjects;

use BestInvestments\Prospecting\Domain\ValueObjects\ProspectIdentifier;

class ProspectIdentifierTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringReturnsValue()
    {
        // Arrange
        $expected   = 'prospect-1234';
        $prospectId = new ProspectIdentifier($expected);

        // Act
        $actual = (string) $prospectId;

        // Assert
        $this->assertSame($expected, $actual);
    }
}
