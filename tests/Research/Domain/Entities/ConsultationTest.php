<?php

namespace BestInvestments\Tests\Research\Domain\Entities;

use BestInvestments\Research\Domain\Entities\Consultation;
use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use BestInvestments\Tests\PrivatePropertyTrait;
use DateTimeImmutable;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use RuntimeException;

class ConsultationTest extends \PHPUnit_Framework_TestCase
{
    use PrivatePropertyTrait;

    public function testIsOpenReturnsTrueWhenOpen()
    {
        // Arrange
        $consultation = $this->getConsultation();

        // Act
        $result = $consultation->isOpen();

        // Assert
        $this->assertTrue($result);
    }

    public function testGetIdReturnsSpecialistId()
    {
        // Arrange
        $consultation = $this->getConsultation();

        // Act
        $actual = $consultation->getId();

        // Assert
        $expected = $this->getInnerPropertyValueByReflection($consultation, 'consultationId');
        $this->assertSame($expected, $actual);
    }

    public function testConsultationId()
    {
        // Arrange
        $consultation = $this->getConsultation();

        // Act
        $actual = $consultation->getId();

        // Assert
        $this->assertTrue(Uuid::isValid($actual->__toString()), 'Consultation identifier is not a UUID');
    }

    public function testIsOpenReturnsFalseWhenNotOpen()
    {
        // Arrange
        $consultation = $this->getConsultation();
        $consultation->discard();

        // Act
        $result = $consultation->isOpen();

        // Assert
        $this->assertFalse($result);
    }

    public function testIsNotOpenReturnsFalseWhenOpen()
    {
        // Arrange
        $consultation = $this->getConsultation();

        // Act
        $result = $consultation->isNotOpen();

        // Assert
        $this->assertFalse($result);
    }

    public function testIsNotOpenReturnsTrueWhenNotOpen()
    {
        // Arrange
        $consultation = $this->getConsultation();
        $consultation->discard();

        // Act
        $result = $consultation->isNotOpen();

        // Assert
        $this->assertTrue($result);
    }

    public function testDiscardThrowsExceptionWhenAlreadyDiscarded()
    {
        // Arrange
        $consultation = $this->getConsultation();
        $consultation->discard();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $consultation->discard();
    }

    public function testDiscardMarksConsultationAsNotOpen()
    {
        // Arrange
        $consultation = $this->getConsultation();

        // Act
        $consultation->discard();

        // Assert
        $this->assertTrue($consultation->isNotOpen());
    }

    public function testIsForSpecialistReturnsTrueWhenSpecialistIsSame()
    {
        // Arrange
        $specialistId = 'specialist-1234';
        $consultation = new Consultation(
            new DateTimeImmutable('2016-12-10'),
            new SpecialistIdentifier($specialistId)
        );

        // Act
        $result = $consultation->isForSpecialist(new SpecialistIdentifier($specialistId));

        // Assert
        $this->assertTrue($result);
    }

    public function testIsForSpecialistReturnsFalseWhenSpecialistIsNotSame()
    {
        // Arrange
        $consultation = new Consultation(
            new DateTimeImmutable('2016-12-10'),
            new SpecialistIdentifier('specialist-1234')
        );

        // Act
        $result = $consultation->isForSpecialist(new SpecialistIdentifier('specialist-9876'));

        // Assert
        $this->assertFalse($result);
    }

    public function testReportTimeThrowsExceptionWhenNotOpen()
    {
        // Arrange
        $consultation = $this->getConsultation();
        $consultation->discard();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $consultation->reportTime(60);
    }

    public function testReportTimeThrowsExceptionWhenDurationIsNotPositive()
    {
        // Arrange
        $consultation = $this->getConsultation();

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $consultation->reportTime(-10);
    }

    public function testReportTimeThrowsExceptionWhenDurationIsZeroPositive()
    {
        // Arrange
        $consultation = $this->getConsultation();

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $consultation->reportTime(0);
    }

    public function testReportTimeChangesStatus()
    {
        // Arrange
        $consultation = $this->getConsultation();

        // Act
        $consultation->reportTime(10);

        // Assert
        $this->assertTrue($consultation->isNotOpen());
    }

    private function getConsultation(): Consultation
    {
        return new Consultation(
            new DateTimeImmutable('2016-12-10'),
            new SpecialistIdentifier('specialist-1234')
        );
    }
}
