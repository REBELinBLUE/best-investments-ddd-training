<?php

namespace BestInvestments\Tests\Invoicing\Domain\ValueObjects;

use BestInvestments\Invoicing\Domain\ValueObjects\PackageLength;
use BestInvestments\Invoicing\Domain\ValueObjects\PackageReference;
use DateTimeImmutable;

class PackageReferenceTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringReturnsExpectedValue()
    {
        // Arrange
        $reference = new PackageReference(
            'gold',
            new DateTimeImmutable('2017-05-01'),
            PackageLength::sixMonths()
        );

        // Act
        $actual = $reference->__toString();

        // Assert
        $this->assertSame('gold-201705-6', $actual);
    }

    public function testGetStartDate()
    {
        // Arrange
        $expected  = new DateTimeImmutable('2017-05-01');
        $reference = new PackageReference('gold', $expected, PackageLength::sixMonths());

        // Act
        $actual = $reference->getStartDate();

        // Assert
        $this->assertSame($expected, $actual);
    }

    public function testGetLength()
    {
        // Arrange
        $expected  = PackageLength::sixMonths();
        $reference = new PackageReference('gold', new DateTimeImmutable('2017-05-01'), $expected);

        // Act
        $actual = $reference->getLength();

        // Assert
        $this->assertSame($expected, $actual);
    }
}
