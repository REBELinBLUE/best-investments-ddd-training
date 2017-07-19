<?php

namespace BestInvestments\Tests\Invoicing\Domain\ValueObjects;

use BestInvestments\Invoicing\Domain\ValueObjects\PackageLength;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageStatus;
use DateTimeImmutable;
use InvalidArgumentException;

class PackageStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideValidStatus
     */
    public function testItAllowsValidStatuses(string $expected)
    {
        // Arrange
        $status = new PackageStatus($expected);

        // Act
        $actual = $status->__toString();

        // Assert
        $this->assertSame($expected, $actual);
    }

    public function provideValidStatus()
    {
        return array_chunk([
            PackageStatus::STARTED,
            PackageStatus::EXPIRED,
            PackageStatus::NOT_STARTED,
        ], 1);
    }

    public function testItDoesNotAllowInvalidStatus()
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Arrange & Act
        new PackageStatus('invalid-status');
    }

    public function testIsReturnsTrueWhenStatusMatches()
    {
        // Arrange
        $expected = PackageStatus::STARTED;
        $status   = new PackageStatus($expected);

        // Act
        $result = $status->is($expected);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsReturnsFalseWhenStatusDoesNotMatch()
    {
        // Arrange
        $status = new PackageStatus(PackageStatus::STARTED);

        // Act
        $result = $status->is(PackageStatus::NOT_STARTED);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsNotReturnsTrueWhenStatusDoesNotMatch()
    {
        // Arrange
        $status = new PackageStatus(PackageStatus::NOT_STARTED);

        // Act
        $result = $status->isNot(PackageStatus::STARTED);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsNotReturnsFalseWhenStatusMatches()
    {
        // Arrange
        $expected = PackageStatus::STARTED;
        $status   = new PackageStatus($expected);

        // Act
        $result = $status->isNot($expected);

        // Assert
        $this->assertFalse($result);
    }

    public function testDetermineReturnsStartedStatusWhenStarted()
    {
        // Arrange
        $status = PackageStatus::determine(new DateTimeImmutable('yesterday'), PackageLength::sixMonths());

        // Act
        $result = $status->is(PackageStatus::STARTED);

        // Assert
        $this->assertTrue($result, 'Package status should be started');
    }

    public function testDetermineReturnsExpiredStatusWhenPackageEndDateHasPassed()
    {
        // Arrange
        $status = PackageStatus::determine(new DateTimeImmutable('2015-10-10'), PackageLength::sixMonths());

        // Act
        $result = $status->is(PackageStatus::EXPIRED);

        // Assert
        $this->assertTrue($result, 'Package status should be expired');
    }

    public function testDetermineReturnsNotStartedStatusWhenPackageHasNotStarted()
    {
        // Arrange
        $status = PackageStatus::determine(new DateTimeImmutable('tomorrow'), PackageLength::sixMonths());

        // Act
        $result = $status->is(PackageStatus::NOT_STARTED);

        // Assert
        $this->assertTrue($result, 'Package status should be not started');
    }
}
