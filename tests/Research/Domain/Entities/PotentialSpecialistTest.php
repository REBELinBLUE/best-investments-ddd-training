<?php

namespace BestInvestments\Tests\Research\Domain\Entities;

use BestInvestments\Research\Domain\Entities\PotentialSpecialist;
use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;

class PotentialSpecialistTest extends \PHPUnit_Framework_TestCase
{
    public function testItRegistersSpecialist()
    {
        // Arrange
        $specialistId = 'specialist-1234';
        $name         = 'John Smith';

        $potentialSpecial = new PotentialSpecialist(new SpecialistIdentifier($specialistId), $name, '');

        // Act
        $specialist = $potentialSpecial->register();

        // Assert
        $this->assertSame($name, $specialist->getName());
        $this->assertSame($specialistId, $specialist->getId()->__toString());
    }
}
