<?php

namespace BestInvestments\Tests\Prospecting\Domain\ValueObjects;

use BestInvestments\Prospecting\Domain\ValueObjects\ProspectStatus;
use InvalidArgumentException;

class ProspectStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideValidStatus
     */
    public function testItAllowsValidStatuses(string $status)
    {
        // Arrange
        $prospectStatus = new ProspectStatus($status);

        // Act
        $actual = $prospectStatus->__toString();

        // Assert
        $this->assertSame($status, $actual);
    }

    public function provideValidStatus()
    {
        return array_chunk([
            ProspectStatus::NEW,
            ProspectStatus::INTERESTED,
            ProspectStatus::NOT_INTERESTED,
            ProspectStatus::REGISTERED,
        ], 1);
    }

    public function testItDoesNotAllowInvalidStatus()
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Arrange & Act
        new ProspectStatus('invalid-status');
    }

    public function testIsReturnsTrueWhenStatusMatches()
    {
        // Arrange
        $expected = ProspectStatus::INTERESTED;
        $status   = new ProspectStatus($expected);

        // Act
        $result = $status->is($expected);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsReturnsFalseWhenStatusDoesNotMatch()
    {
        // Arrange
        $status = new ProspectStatus(ProspectStatus::INTERESTED);

        // Act
        $result = $status->is(ProspectStatus::NEW);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsNotReturnsTrueWhenStatusDoesNotMatch()
    {
        // Arrange
        $status = new ProspectStatus(ProspectStatus::NEW);

        // Act
        $result = $status->isNot(ProspectStatus::INTERESTED);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsNotReturnsFalseWhenStatusMatches()
    {
        // Arrange
        $expected = ProspectStatus::INTERESTED;
        $status   = new ProspectStatus($expected);

        // Act
        $result = $status->isNot($expected);

        // Assert
        $this->assertFalse($result);
    }
}
