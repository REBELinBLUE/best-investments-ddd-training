<?php

namespace BestInvestments\Tests\Prospecting\Domain\Entities;

use BestInvestments\Prospecting\Domain\Entities\Prospect;
use BestInvestments\Prospecting\Domain\ValueObjects\ProspectIdentifier;
use BestInvestments\Prospecting\Domain\ValueObjects\ProspectStatus;
use BestInvestments\Tests\PrivatePropertyTrait;
use RuntimeException;

class ProspectTest extends \PHPUnit_Framework_TestCase
{
    use PrivatePropertyTrait;

    public function testItCreatesNewProspect()
    {
        // Arrange
        $prospectId = new ProspectIdentifier('prospect-1234');

        // Act
        $prospect = new Prospect($prospectId, 'John Doe', 'john.doe@example.com');

        // Assert
        $this->assertSame($prospectId, $this->getInnerPropertyValueByReflection($prospect, 'prospectId'));

        /** @var ProspectStatus $status */
        $status = $this->getInnerPropertyValueByReflection($prospect, 'status');

        $this->assertTrue($status->is(ProspectStatus::NEW), 'Status should be new');
    }

    public function testInterested()
    {
        // Arrange
        $prospect = $this->getProspect();

        // Act
        $prospect->interested();

        // Assert
        /** @var ProspectStatus $status */
        $status = $this->getInnerPropertyValueByReflection($prospect, 'status');

        $this->assertTrue($status->is(ProspectStatus::INTERESTED), 'Status should be interested');
    }

    public function testInterestedThrowAnExceptionIfAlreadyRegistered()
    {
        // Arrange
        $prospect = $this->getProspect();
        $prospect->interested();
        $prospect->register();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $prospect->interested();
    }

    public function testNotInterested()
    {
        // Arrange
        $prospect = $this->getProspect();

        // Act
        $prospect->notInterested();

        // Assert
        /** @var ProspectStatus $status */
        $status = $this->getInnerPropertyValueByReflection($prospect, 'status');

        $this->assertTrue($status->is(ProspectStatus::NOT_INTERESTED), 'Status should be not interested');
    }

    public function testNotInterestedThrowAnExceptionIfAlreadyRegistered()
    {
        // Arrange
        $prospect = $this->getProspect();
        $prospect->interested();
        $prospect->register();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $prospect->notInterested();
    }

    public function testRegister()
    {
        // Arrange
        $prospect = $this->getProspect();
        $prospect->interested();

        // Act
        $prospect->register();

        // Assert
        /** @var ProspectStatus $status */
        $status = $this->getInnerPropertyValueByReflection($prospect, 'status');

        $this->assertTrue($status->is(ProspectStatus::REGISTERED), 'Status should be registered');
    }

    public function testRegisterThrowAnExceptionIfAlreadyNotInterested()
    {
        // Arrange
        $prospect = $this->getProspect();
        $prospect->notInterested();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $prospect->register();
    }

    private function getProspect(): Prospect
    {
        return new Prospect(
            new ProspectIdentifier('prospect-1234'),
            'John Doe',
            'john.doe@example.com'
        );
    }
}
