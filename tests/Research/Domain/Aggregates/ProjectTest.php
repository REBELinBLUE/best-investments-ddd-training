<?php

namespace BestInvestments\Tests\Research\Domain\Aggregates;

use BestInvestments\Research\Domain\Aggregates\Collections\ConsultationList;
use BestInvestments\Research\Domain\Aggregates\Collections\SpecialistList;
use BestInvestments\Research\Domain\Aggregates\Project;
use BestInvestments\Research\Domain\ValueObjects\ClientIdentifier;
use BestInvestments\Research\Domain\ValueObjects\ManagerIdentifier;
use BestInvestments\Research\Domain\ValueObjects\ProjectReference;
use BestInvestments\Research\Domain\ValueObjects\ProjectStatus;
use BestInvestments\Research\Domain\ValueObjects\SpecialistIdentifier;
use BestInvestments\Tests\PrivatePropertyTrait;
use DateTimeImmutable;
use RuntimeException;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    use PrivatePropertyTrait;

    public function testDraft()
    {
        // Arrange
        $name     = 'project-1234';
        $deadline = new DateTimeImmutable('2017-06-10');
        $clientId = new ClientIdentifier('client-1234');

        // Act
        $project = Project::draft($name, $deadline, $clientId);

        // Assert
        $this->assertSame($name, $this->getInnerPropertyValueByReflection($project, 'name'));
        $this->assertSame($deadline, $this->getInnerPropertyValueByReflection($project, 'deadline'));
        $this->assertSame($clientId, $this->getInnerPropertyValueByReflection($project, 'clientId'));

        /** @var ProjectStatus $status */
        $status = $this->getInnerPropertyValueByReflection($project, 'status');

        $this->assertTrue($status->is(ProjectStatus::DRAFT), 'Status should be draft');

        /** @var ProjectReference $reference */
        $reference = $this->getInnerPropertyValueByReflection($project, 'reference');

        $this->assertInstanceOf(ProjectReference::class, $reference);

        /** @var SpecialistList $approvedSpecialists */
        $approvedSpecialists = $this->getInnerPropertyValueByReflection($project, 'approvedSpecialists');

        /** @var SpecialistList $unapprovedSpecialists */
        $unapprovedSpecialists = $this->getInnerPropertyValueByReflection($project, 'unapprovedSpecialists');

        /** @var SpecialistList $discardedSpecialists */
        $discardedSpecialists = $this->getInnerPropertyValueByReflection($project, 'discardedSpecialists');

        /** @var ConsultationList $consultations */
        $consultations = $this->getInnerPropertyValueByReflection($project, 'consultations');

        $this->assertInstanceOf(SpecialistList::class, $approvedSpecialists);
        $this->assertInstanceOf(SpecialistList::class, $unapprovedSpecialists);
        $this->assertInstanceOf(SpecialistList::class, $discardedSpecialists);
        $this->assertInstanceOf(ConsultationList::class, $consultations);
    }

    public function testStart()
    {
        // Arrange
        $deadline  = new DateTimeImmutable('2017-06-10');
        $clientId  = new ClientIdentifier('client-1234');
        $managerId = new ManagerIdentifier('manager-1234');

        $project = Project::draft('project-1234', $deadline, $clientId);

        // Act
        $project->start($managerId);

        // Assert
        /** @var ProjectStatus $status */
        $status = $this->getInnerPropertyValueByReflection($project, 'status');

        $this->assertSame($managerId, $this->getInnerPropertyValueByReflection($project, 'managerId'));
        $this->assertTrue($status->is(ProjectStatus::ACTIVE), 'Status should be active');
    }

    public function testStartThrowsExceptionIfAlreadyStarted()
    {
        // Arrange
        $project = $this->getStartedProject();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->start(new ManagerIdentifier('manager-1234'));
    }

    public function testStartThrowsExceptionIfAlreadyClosed()
    {
        // Arrange
        $project = $this->getStartedProject();
        $project->close();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->start(new ManagerIdentifier('manager-1234'));
    }

    public function testClose()
    {
        // Arrange
        $project = $this->getStartedProject();

        // Assert
        $project->close();

        // Act
        /** @var ProjectStatus $status */
        $status = $this->getInnerPropertyValueByReflection($project, 'status');

        $this->assertTrue($status->is(ProjectStatus::CLOSED), 'Status should be active');
    }

    public function testCloseThrowsExceptionIfNotStarted()
    {
        // Arrange
        $project = $this->getStartedProject();
        $project->close();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->close();
    }

    public function testCloseThrowsExceptionIfThereAreStillOpenConsultations()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');

        $project = $this->getStartedProject();
        $project->addSpecialist($specialistId);
        $project->approveSpecialist($specialistId);

        $project->scheduleConsultation(
            new DateTimeImmutable('2017-06-12'),
            $specialistId
        );

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->close();
    }

    public function testCloseThrowsExceptionIfAlreadyClosed()
    {
        // Arrange
        $project = $this->getProject();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->close();
    }

    public function testAddSpecialist()
    {
        // Arrange
        $project      = $this->getStartedProject();
        $specialistId = new SpecialistIdentifier('specialist-1234');

        // Act
        $project->addSpecialist($specialistId);

        // Assert
        /** @var SpecialistList $unapprovedSpecialists */
        $unapprovedSpecialists = $this->getInnerPropertyValueByReflection($project, 'unapprovedSpecialists');

        $this->assertTrue(
            $unapprovedSpecialists->contains($specialistId),
            'Unapproved specialist does not contain expected specialist'
        );
    }

    public function testAddSpecialistThrowsExceptionWhenNotStarted()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');
        $project      = $this->getProject();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->addSpecialist($specialistId);
    }

    public function testAddSpecialistThrowsExceptionWhenAlreadyUnapproved()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');

        $project = $this->getStartedProject();
        $project->addSpecialist($specialistId);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->addSpecialist($specialistId);
    }

    public function testAddSpecialistThrowsExceptionWhenAlreadyApproved()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');

        $project = $this->getStartedProject();
        $project->addSpecialist($specialistId);
        $project->approveSpecialist($specialistId);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->addSpecialist($specialistId);
    }

    public function testAddSpecialistThrowsExceptionWhenAlreadyDiscarded()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');

        $project = $this->getStartedProject();
        $project->addSpecialist($specialistId);
        $project->discardSpecialist($specialistId);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->addSpecialist($specialistId);
    }

    public function testApproveSpecialist()
    {
        // Arrange
        $project      = $this->getStartedProject();
        $specialistId = new SpecialistIdentifier('specialist-1234');
        $project->addSpecialist($specialistId);

        // Act
        $project->approveSpecialist($specialistId);

        // Assert
        /** @var SpecialistList $approvedSpecialists */
        $approvedSpecialists = $this->getInnerPropertyValueByReflection($project, 'approvedSpecialists');

        /** @var SpecialistList $unapprovedSpecialists */
        $unapprovedSpecialists = $this->getInnerPropertyValueByReflection($project, 'unapprovedSpecialists');

        $this->assertTrue(
            $approvedSpecialists->contains($specialistId),
            'Approved specialist does not contain expected specialist'
        );

        $this->assertTrue(
            $unapprovedSpecialists->doesNotContain($specialistId),
            'Unapproved specialist does not contain expected specialist'
        );
    }

    public function testApproveSpecialistThrowsExceptionWhenNotStarted()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');
        $project      = $this->getProject();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->approveSpecialist($specialistId);
    }

    public function testApproveSpecialistThrowsExceptionWhenNotCurrentlyUnapproved()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');
        $project      = $this->getStartedProject();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->approveSpecialist($specialistId);
    }

    public function testApproveSpecialistThrowsExceptionWhenNotAlreadyApproved()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');
        $project      = $this->getStartedProject();
        $project->addSpecialist($specialistId);
        $project->approveSpecialist($specialistId);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->approveSpecialist($specialistId);
    }

    public function testDiscardSpecialist()
    {
        // Arrange
        $project      = $this->getStartedProject();
        $specialistId = new SpecialistIdentifier('specialist-1234');
        $project->addSpecialist($specialistId);

        // Act
        $project->discardSpecialist($specialistId);

        // Assert
        /** @var SpecialistList $discardedSpecialists */
        $discardedSpecialists = $this->getInnerPropertyValueByReflection($project, 'discardedSpecialists');

        /** @var SpecialistList $unapprovedSpecialists */
        $unapprovedSpecialists = $this->getInnerPropertyValueByReflection($project, 'unapprovedSpecialists');

        $this->assertTrue(
            $discardedSpecialists->contains($specialistId),
            'Discarded specialist does not contain expected specialist'
        );

        $this->assertTrue(
            $unapprovedSpecialists->doesNotContain($specialistId),
            'Unapproved specialist does not contain expected specialist'
        );
    }

    public function testDiscardSpecialistThrowsExceptionWhenNotStarted()
    {
        // Arrange
        $project = $this->getProject();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->discardSpecialist(new SpecialistIdentifier('specialist-1234'));
    }

    public function testDiscardSpecialistThrowsExceptionWhenNotCurrentlyUnapproved()
    {
        // Arrange
        $project = $this->getStartedProject();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->discardSpecialist(new SpecialistIdentifier('specialist-1234'));
    }

    public function testDiscardSpecialistThrowsExceptionWhenNotAlreadyDiscarded()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');
        $project      = $this->getStartedProject();
        $project->addSpecialist($specialistId);
        $project->discardSpecialist($specialistId);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->discardSpecialist($specialistId);
    }

    public function testScheduleConsultation()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');
        $date         = new DateTimeImmutable('2017-05-10');

        $project = $this->getStartedProject();
        $project->addSpecialist($specialistId);
        $project->approveSpecialist($specialistId);

        // Act
        $consultationId = $project->scheduleConsultation($date, $specialistId);

        // Assert
        /** @var ConsultationList $consultations */
        $consultations = $this->getInnerPropertyValueByReflection($project, 'consultations');

        $consultation = $consultations->getOpenConsultationForSpecialist($specialistId);

        /** @var DateTimeImmutable $consultationDate */
        $consultationDate = $this->getInnerPropertyValueByReflection($consultation, 'startTime');

        /** @var SpecialistIdentifier $consultationSpecialistId */
        $consultationSpecialistId = $this->getInnerPropertyValueByReflection($consultation, 'specialistId');

        $this->assertSame($consultation->getId(), $consultationId);
        $this->assertSame($date, $consultationDate);
        $this->assertSame($specialistId, $consultationSpecialistId);
    }

    public function testScheduleConsultationThrowsAnExceptionWhenNotStarted()
    {
        // Arrange
        $project = $this->getProject();

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->scheduleConsultation(
            new DateTimeImmutable('2017-05-10'),
            new SpecialistIdentifier('specialist-1234')
        );
    }

    public function testScheduleConsultationThrowsAnExceptionWhenSpecialistIsUnapproved()
    {
        // Arrange
        $project      = $this->getStartedProject();
        $specialistId = new SpecialistIdentifier('specialist-1234');
        $project->addSpecialist($specialistId);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->scheduleConsultation(new DateTimeImmutable('2017-05-10'), $specialistId);
    }

    public function testScheduleConsultationThrowsAnExceptionWhenSpecialistIsDiscarded()
    {
        // Arrange
        $project      = $this->getStartedProject();
        $specialistId = new SpecialistIdentifier('specialist-1234');
        $project->addSpecialist($specialistId);
        $project->discardSpecialist($specialistId);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->scheduleConsultation(new DateTimeImmutable('2017-05-10'), $specialistId);
    }

    public function testScheduleConsultationThrowsAnExceptionWhenSpecialistAlreadyHasAnOpenConsultation()
    {
        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');

        $project = $this->getStartedProjectWithApprovedSpecialist($specialistId);
        $project->scheduleConsultation(new DateTimeImmutable('2017-05-10'), $specialistId);

        // Assert
        $this->expectException(RuntimeException::class);

        // Act
        $project->scheduleConsultation(new DateTimeImmutable('2017-06-05'), $specialistId);
    }

    public function testReportConsultation()
    {
        $this->markTestSkipped('Broken');

        // Arrange
        $specialistId = new SpecialistIdentifier('specialist-1234');

        $project = $this->getStartedProjectWithApprovedSpecialist($specialistId);
        $consultationId = $project->scheduleConsultation(new DateTimeImmutable('2017-06-05'), $specialistId);

        // Act
        $project->reportConsultation($consultationId, 100);

        // Assert
    }

    private function getProject(): Project
    {
        return Project::draft(
            'project-1234',
            new DateTimeImmutable('2017-06-10'),
            new ClientIdentifier('client-1234')
        );
    }

    private function getStartedProject(): Project
    {
        $project = $this->getProject();
        $project->start(new ManagerIdentifier('manager-1234'));

        return $project;
    }

    private function getStartedProjectWithApprovedSpecialist(SpecialistIdentifier $specialistId): Project
    {
        $project = $this->getStartedProject();
        $project->addSpecialist($specialistId);
        $project->approveSpecialist($specialistId);

        return $project;
    }
}
