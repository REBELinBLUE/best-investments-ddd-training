<?php

namespace BestInvestments\Tests\Research\Domain\ValueObjects;

use BestInvestments\Research\Domain\ValueObjects\ConsultationStatus;
use InvalidArgumentException;

class ConsultationStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideValidStatus
     */
    public function testItAllowsValidStatuses(string $status)
    {
        // Arrange
        $consultationStatus = new ConsultationStatus($status);

        // Act
        $actual = $consultationStatus->__toString();

        // Assert
        $this->assertSame($status, $actual);
    }

    public function provideValidStatus()
    {
        return array_chunk([
            ConsultationStatus::OPENED,
            ConsultationStatus::DISCARDED,
            ConsultationStatus::CONFIRMED,
        ], 1);
    }

    public function testItDoesNotAllowInvalidStatus()
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Arrange & Act
        new ConsultationStatus('invalid-status');
    }

    public function testIsReturnsTrueWhenStatusMatches()
    {
        // Arrange
        $expected = ConsultationStatus::OPENED;
        $status   = new ConsultationStatus($expected);

        // Act
        $result = $status->is($expected);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsReturnsFalseWhenStatusDoesNotMatch()
    {
        // Arrange
        $status = new ConsultationStatus(ConsultationStatus::OPENED);

        // Act
        $result = $status->is(ConsultationStatus::CONFIRMED);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsNotReturnsTrueWhenStatusDoesNotMatch()
    {
        // Arrange
        $status = new ConsultationStatus(ConsultationStatus::OPENED);

        // Act
        $result = $status->isNot(ConsultationStatus::CONFIRMED);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsNotReturnsFalseWhenStatusMatches()
    {
        // Arrange
        $expected = ConsultationStatus::OPENED;
        $status   = new ConsultationStatus($expected);

        // Act
        $result = $status->isNot($expected);

        // Assert
        $this->assertFalse($result);
    }
}
