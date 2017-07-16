<?php

namespace BestInvestments\Tests\Research\Domain\ValueObjects;

use BestInvestments\Research\Domain\ValueObjects\ManagerIdentifier;

class ManagerIndentifierTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringReturnsValue()
    {
        // Arrange
        $expected  = 'manager-1234';
        $managerId = new ManagerIdentifier($expected);

        // Act
        $actual = (string) $managerId;

        // Assert
        $this->assertSame($expected, $actual);
    }
}
