<?php

namespace BestInvestments\Tests\Research\Domain\Aggregates\Collections;

use BestInvestments\Research\Domain\Aggregates\Collections\SpecialistList;
use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use BestInvestments\Tests\PrivatePropertyTrait;

class SpecialistListTest extends \PHPUnit_Framework_TestCase
{
    use PrivatePropertyTrait;

    public function testAddPushesConsultationToCollection()
    {
        // Arrange
        $specialistList = new SpecialistList();
        $specialistId   = new SpecialistIdentifier('specialist-1234');

        // Act
        $specialistList->add($specialistId);

        // Assert
        $collection = $this->getInnerPropertyValueByReflection($specialistList, 'collection');
        $this->assertSame(1, $collection->count());
        $this->assertSame($collection->pop(), $specialistId);
    }

    public function testRemovePullsConsultationFromCollection()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');

        $specialistList = new SpecialistList();
        $specialistList->add($specialistId);

        // Act
        $specialistList->remove($specialistId);

        // Assert
        $collection = $this->getInnerPropertyValueByReflection($specialistList, 'collection');
        $this->assertSame(0, $collection->count());
    }

    public function testContainsReturnsTrueWhenSpecialistIdIsInCollection()
    {
        // Arrange
        $specialistId   = new SpecialistIdentifier('specialist-1234');
        $specialistList = new SpecialistList();
        $specialistList->add($specialistId);

        // Act
        $result = $specialistList->contains($specialistId);

        // Assert
        $this->assertTrue($result);
    }

    public function testContainsReturnsFalseWhenSpecialistIdIsNotInCollection()
    {
        // Arrange
        $specialistList = new SpecialistList();
        $specialistList->add(new SpecialistIdentifier('specialist-1234'));

        // Act
        $result = $specialistList->contains(new SpecialistIdentifier('specialist-9876'));

        // Assert
        $this->assertFalse($result);
    }

    public function testDoesNotContainReturnsTrueWhenSpecialistIdIsNotInCollection()
    {
        // Arrange
        $specialistList = new SpecialistList();
        $specialistList->add(new SpecialistIdentifier('specialist-1234'));

        // Act
        $result = $specialistList->doesNotContain(new SpecialistIdentifier('specialist-9876'));

        // Assert
        $this->assertTrue($result);
    }

    public function testDoesNotContainReturnsFlaseWhenSpecialistIdIsInCollection()
    {
        // Arrange
        $specialistId   = new SpecialistIdentifier('specialist-1234');
        $specialistList = new SpecialistList();
        $specialistList->add($specialistId);

        // Act
        $result = $specialistList->doesNotContain($specialistId);

        // Assert
        $this->assertFalse($result);
    }
}
