<?php

namespace BestInvestments\Tests\Invoicing\Domain\ValueObjects;

use BestInvestments\Invoicing\Domain\ValueObjects\PackageLength;
use InvalidArgumentException;

class PackageLengthTest extends \PHPUnit_Framework_TestCase
{
    public function testSixMonths()
    {
        // Arrange
        $length = PackageLength::sixMonths();

        // Act
        $actual = (int) $length->__toString();

        // Assert
        $this->assertSame(6, $actual);
    }

    public function testTwelveMonths()
    {
        // Arrange
        $length = PackageLength::twelveMonths();

        // Act
        $actual = (int) $length->__toString();

        // Assert
        $this->assertSame(12, $actual);
    }

    /**
     * @dataProvider provideValidLengths
     */
    public function testItAcceptsValidLengths($expected)
    {
        // Arrange
        $length = new PackageLength($expected);

        // Act
        $actual = (int) $length->__toString();

        // Assert
        $this->assertSame($expected, $actual);
    }

    public function provideValidLengths()
    {
        return array_chunk([6, 12], 1);
    }

    /**
     * @dataProvider provideInvalidLengths
     */
    public function testItDoesNotAcceptInValidLengths($length)
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        new PackageLength($length);
    }

    public function provideInvalidLengths()
    {
        return array_chunk([1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 13], 1);
    }
}
