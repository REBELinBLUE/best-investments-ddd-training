<?php

namespace BestInvestments\Tests\Research\Domain\Entities;

use BestInvestments\Research\Domain\Entities\Specialist;
use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;

class SpecialistTest extends \PHPUnit_Framework_TestCase
{
    public function testGetId()
    {
        // Arrange
        $expected   = 'John Smith';
        $specialist = new Specialist(new SpecialistIdentifier('specialist-1234'), $expected);

        // Act
        $actual = $specialist->getName();

        // Assert
        $this->assertsAME($expected, $actual);
    }

    public function testGetName()
    {
        // Arrange
        $expected   = new SpecialistIdentifier('specialist-1234');
        $specialist = new Specialist($expected, 'John Smith');

        // Act
        $actual = $specialist->getId();

        // Assert
        $this->assertsAME($expected, $actual);
    }
}
