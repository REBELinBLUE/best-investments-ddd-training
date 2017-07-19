<?php

namespace BestInvestments\Tests\Invoicing\Domain\ValueObjects;

use BestInvestments\Invoicing\Domain\ValueObjects\ClientIdentifier;

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

    public function testIsReturnsTrueWhenClientIdentiferMatches()
    {
        // Arrange
        $client = new ClientIdentifier('client-1234');

        // Act
        $result = $client->is(new ClientIdentifier('client-1234'));

        // Assert
        $this->assertTrue($result);
    }

    public function testIsReturnsFalseWhenClientIdentiferDoesNotMatch()
    {
        // Arrange
        $client = new ClientIdentifier('client-1234');

        // Act
        $result = $client->is(new ClientIdentifier('client-9876'));

        // Assert
        $this->assertFalse($result);
    }

    public function testIsNotReturnsTrueWhenClientIdentiferDoesNotMatch()
    {
        // Arrange
        $client = new ClientIdentifier('client-1234');

        // Act
        $result = $client->isNot(new ClientIdentifier('client-9876'));

        // Assert
        $this->assertTrue($result);
    }

    public function testIsNotReturnsFalseWhenClientIdentiferMatches()
    {
        // Arrange
        $client = new ClientIdentifier('client-1234');

        // Act
        $result = $client->isNot(new ClientIdentifier('client-1234'));

        // Assert
        $this->assertFalse($result);
    }
}
