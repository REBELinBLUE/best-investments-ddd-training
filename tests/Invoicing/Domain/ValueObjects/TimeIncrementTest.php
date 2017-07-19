<?php

namespace BestInvestments\Tests\Invoicing\Domain\ValueObjects;

use BestInvestments\Invoicing\Domain\ValueObjects\TimeIncrement;
use BestInvestments\Tests\PrivatePropertyTrait;

class TimeIncrementTest extends \PHPUnit_Framework_TestCase
{
    use PrivatePropertyTrait;

    public function testItThrowsAnExceptionWhenMinutesIsNotPositive()
    {
        // Assert
        $this->expectException(\InvalidArgumentException::class);

        // Act
        new TimeIncrement(-10);
    }

    /**
     * @dataProvider provideTimes
     */
    public function testIncrementsAreCalculatedCorrectly($minutes, $expected)
    {
        // Act
        $timeIncrement = new TimeIncrement($minutes);

        // Assert
        /** @var int $increments */
        $increments = $this->getInnerPropertyValueByReflection($timeIncrement, 'increments');

        $this->assertSame(
            $expected,
            $increments,
            "{$minutes} minutes should be {$expected} increments, got {$increments}"
        );
    }

    public function provideTimes()
    {
        return [
            [0, 0],
            [5, 1],
            [10, 1],
            [15, 1],
            [16, 2],
            [30, 2],
            [35, 3],
            [45, 3],
            [50, 4],
            [60, 4],
            [120, 8],
        ];
    }

    public function testAdd()
    {
        // Arrange
        $timeIncrement = new TimeIncrement(10);

        // Act
        $result = $timeIncrement->add(new TimeIncrement(28));

        // Assert
        /** @var int $increments */
        $increments = $this->getInnerPropertyValueByReflection($result, 'increments');

        $this->assertSame(3, $increments);
        $this->assertSame(38, $result->getMinutes());
    }

    public function testSubstract()
    {
        // Arrange
        $timeIncrement = new TimeIncrement(55);

        // Act
        $result = $timeIncrement->subtract(new TimeIncrement(28));

        // Assert
        /** @var int $increments */
        $increments = $this->getInnerPropertyValueByReflection($result, 'increments');

        $this->assertSame(2, $increments);
        $this->assertSame(27, $result->getMinutes());
    }

    public function testGetMinutes()
    {
        // Arrange
        $expected      = 45;
        $timeIncrement = new TimeIncrement($expected);

        // Act
        $actual = $timeIncrement->getMinutes();

        // Assert
        $this->assertSame($expected, $actual);
    }

    public function testIsMoreThanReturnsTrueWhenMoreThan()
    {
        // Arrange
        $timeIncrement = new TimeIncrement(45);

        // Act
        $result = $timeIncrement->isMoreThan(new TimeIncrement(30));

        // Assert
        $this->assertTrue($result);
    }

    public function testIsMoreThanReturnsFalseWhenLessThan()
    {
        // Arrange
        $timeIncrement = new TimeIncrement(45);

        // Act
        $result = $timeIncrement->isMoreThan(new TimeIncrement(60));

        // Assert
        $this->assertFalse($result);
    }

    public function testIsMoreThanReturnsFalseWhenEqual()
    {
        // Arrange
        $timeIncrement = new TimeIncrement(45);

        // Act
        $result = $timeIncrement->isMoreThan(new TimeIncrement(45));

        // Assert
        $this->assertFalse($result);
    }
}
