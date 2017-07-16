<?php

namespace BestInvestments\Tests\Research\Domain\ValueObjects;

use BestInvestments\Research\Domain\ValueObjects\ClientIdentifier;

class ClientIdentifierTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringReturnsValue()
    {
        // Arrange
        $expected = 'client-1234';
        $clientId = new ClientIdentifier($expected);

        // Act
        $actual = (string) $clientId;

        // Assert
        $this->assertSame($expected, $actual);
    }
}
