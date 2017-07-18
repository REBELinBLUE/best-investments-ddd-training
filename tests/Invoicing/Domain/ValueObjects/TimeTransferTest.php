<?php

namespace BestInvestments\Tests\Invoicing\Domain\ValueObjects;

use BestInvestments\Invoicing\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Invoicing\Domain\ValueObjects\TimeIncrement;
use BestInvestments\Invoicing\Domain\ValueObjects\TimeTransfer;

class TimeTransferTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTime()
    {
        // Arrange
        $expected = new TimeIncrement(46);
        $tranfer  = new TimeTransfer($expected, new ClientIdentifier('client-1234'));

        // Act
        $actual = $tranfer->getTime();

        // Assert
        $this->assertSame($expected, $actual);
    }

    public function testIsForClientReturnsTrueWhenClientMatches()
    {
        // Arrange
        $clientId = new ClientIdentifier('client-1234');
        $tranfer  = new TimeTransfer(new TimeIncrement(46), $clientId);

        // Act
        $result = $tranfer->isForClient($clientId);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsForClientReturnsFalseWhenClientDoesNotMatch()
    {
        // Arrange
        $tranfer = new TimeTransfer(new TimeIncrement(46), new ClientIdentifier('client-1234'));

        // Act
        $result = $tranfer->isForClient(new ClientIdentifier('client-9876'));

        // Assert
        $this->assertFalse($result);
    }
}
