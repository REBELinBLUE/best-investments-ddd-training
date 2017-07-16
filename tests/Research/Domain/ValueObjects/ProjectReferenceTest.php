<?php

namespace BestInvestments\Tests\Research\Domain\ValueObjects;

use BestInvestments\Research\Domain\ValueObjects\ProjectReference;

class ProjectReferenceTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringReturnsValue()
    {
        // Arrange
        $projectReference = new ProjectReference();

        // Act
        $actual = (string) $projectReference;

        // Assert
        $this->assertRegExp(
            '/^[A-Z]{2}[0-9]{4}$/',
            $actual,
            'The project reference should be 2 capital letters followed by 4 digits'
        );
    }
}
