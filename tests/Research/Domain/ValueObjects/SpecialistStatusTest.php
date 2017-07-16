<?php

namespace BestInvestments\Tests\Research\Domain\ValueObjects;

use BestInvestments\Research\Domain\ValueObjects\SpecialistStatus;
use InvalidArgumentException;

class SpecialistStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideValidStatus
     */
    public function testItAllowsValidStatuses(string $status)
    {
        // Arrange
        $specialistStatus = new SpecialistStatus($status);

        // Act
        $actual = $specialistStatus->__toString();

        // Assert
        $this->assertSame($status, $actual);
    }

    public function provideValidStatus()
    {
        return array_chunk([
            SpecialistStatus::UNKNOWN,
            SpecialistStatus::APPROVED,
            SpecialistStatus::DISCARDED,
        ], 1);
    }

    public function testItDoesNotAllowInvalidStatus()
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Arrange & Act
        new SpecialistStatus('invalid-status');
    }

    public function testIsReturnsTrueWhenStatusMatches()
    {
        // Arrange
        $expected = SpecialistStatus::APPROVED;
        $status   = new SpecialistStatus($expected);

        // Act
        $result = $status->is($expected);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsReturnsFalseWhenStatusDoesNotMatch()
    {
        // Arrange
        $status = new SpecialistStatus(SpecialistStatus::APPROVED);

        // Act
        $result = $status->is(SpecialistStatus::DISCARDED);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsNotReturnsTrueWhenStatusDoesNotMatch()
    {
        // Arrange
        $status = new SpecialistStatus(SpecialistStatus::APPROVED);

        // Act
        $result = $status->isNot(SpecialistStatus::DISCARDED);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsNotReturnsFalseWhenStatusMatches()
    {
        // Arrange
        $expected = SpecialistStatus::APPROVED;
        $status   = new SpecialistStatus($expected);

        // Act
        $result = $status->isNot($expected);

        // Assert
        $this->assertFalse($result);
    }
}
