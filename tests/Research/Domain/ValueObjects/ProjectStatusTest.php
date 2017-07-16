<?php

namespace BestInvestments\Tests\Research\Domain\ValueObjects;

use BestInvestments\Research\Domain\ValueObjects\ProjectStatus;
use InvalidArgumentException;

class ProjectStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideValidStatus
     */
    public function testItAllowsValidStatuses(string $status)
    {
        // Arrange
        $projectStatus = new ProjectStatus($status);

        // Act
        $actual = $projectStatus->__toString();

        // Assert
        $this->assertSame($status, $actual);
    }

    public function provideValidStatus()
    {
        return array_chunk([
            ProjectStatus::DRAFT,
            ProjectStatus::ACTIVE,
            ProjectStatus::CLOSED,
        ], 1);
    }

    public function testItDoesNotAllowInvalidStatus()
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Arrange & Act
        new ProjectStatus('invalid-status');
    }

    public function testIsReturnsTrueWhenStatusMatches()
    {
        // Arrange
        $expected = ProjectStatus::ACTIVE;
        $status   = new ProjectStatus($expected);

        // Act
        $result = $status->is($expected);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsReturnsFalseWhenStatusDoesNotMatch()
    {
        // Arrange
        $status = new ProjectStatus(ProjectStatus::ACTIVE);

        // Act
        $result = $status->is(ProjectStatus::CLOSED);

        // Assert
        $this->assertFalse($result);
    }

    public function testIsNotReturnsTrueWhenStatusDoesNotMatch()
    {
        // Arrange
        $status = new ProjectStatus(ProjectStatus::ACTIVE);

        // Act
        $result = $status->isNot(ProjectStatus::CLOSED);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsNotReturnsFalseWhenStatusMatches()
    {
        // Arrange
        $expected = ProjectStatus::ACTIVE;
        $status   = new ProjectStatus($expected);

        // Act
        $result = $status->isNot($expected);

        // Assert
        $this->assertFalse($result);
    }
}
