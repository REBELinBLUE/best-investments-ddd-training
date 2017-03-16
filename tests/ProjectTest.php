<?php

use App\ValueObjects\ClientIdentifer;
use App\ValueObjects\ManagerIdentifer;
use App\ValueObjects\ProjectReference;
use App\ValueObjects\ProjectStatus;
use App\ValueObjects\SpecialistIdentifer;
use Illuminate\Support\Collection;
use Mockery as m;
use App\Project;

/**
 * @coversDefaultClass \App\Project
 */
class ProjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::draft
     * @covers ::__construct
     * @covers ::getDeadline
     * @covers ::getName
     * @covers ::getClientId
     * @covers ::getReference
     * @covers ::getStatus
     */
    public function testDraft()
    {
        $expectedName = 'a-project';
        $expectedDeadline = new DateTime();
        $expectedClientId = new ClientIdentifer(1234);

        $project = Project::draft($expectedName, $expectedDeadline, $expectedClientId);

        $this->assertSame($expectedName, $project->getName());
        $this->assertSame($expectedDeadline, $project->getDeadline());
        $this->assertSame($expectedClientId, $project->getClientId());
        $this->assertTrue($project->getStatus()->is(ProjectStatus::DRAFT), "Not in draft status");
        $this->assertInstanceOf(ProjectReference::class, $project->getReference());
    }

    /**
     * @covers ::start
     * @covers ::getManagerId
     * @covers ::getSpecialists
     */
    public function testStart()
    {
        $expectedManager = new ManagerIdentifer(8765);

        $project = Project::draft('name', new DateTime(), new ClientIdentifer(1234));
        $project->start($expectedManager);

        $this->assertSame($expectedManager, $project->getManagerId());
        $this->assertInstanceOf(Collection::class, $project->getSpecialists());
        $this->assertSame(0, $project->getSpecialists()->count());
    }

    /**
     * @covers ::start
     */
    public function testStartThrowsExceptionWhenAlreadyStarted()
    {
        $manager = new ManagerIdentifer(8765);

        $project = Project::draft('name', new DateTime(), new ClientIdentifer(1234));
        $project->start($manager);

        $this->expectException(RuntimeException::class);

        $project->start($manager);
    }

    /**
     * @covers ::addSpecialist
     * @covers ::getSpecialists
     */
    public function testAddSpecialist()
    {
        $project = Project::draft('name', new DateTime(), new ClientIdentifer(6785));
        $project->start(new ManagerIdentifer(5467));

        $project->addSpecialist(new SpecialistIdentifer(3281));

        $this->assertSame(1, $project->getSpecialists()->count());

        $project->addSpecialist(new SpecialistIdentifer(2682));

        $this->assertSame(2, $project->getSpecialists()->count());
    }

    /**
     * @covers ::addSpecialist
     */
    public function testAddSpecialistThrowsExceptionWhenNotStarted()
    {
        $this->expectException(RuntimeException::class);

        $project = Project::draft('name', new DateTime(), new ClientIdentifer(6785));
        $project->addSpecialist(new SpecialistIdentifer(3281));
    }

    /**
     * @covers ::addSpecialist
     */
    public function testAddSpecialistThrowsExceptionWhenSpecialistAlreadyAdded()
    {
        $expectedSpecialist = new SpecialistIdentifer(3281);

        $this->expectException(RuntimeException::class);

        $project = Project::draft('name', new DateTime(), new ClientIdentifer(6785));
        $project->start(new ManagerIdentifer(5467));

        $project->addSpecialist($expectedSpecialist);
        $project->addSpecialist($expectedSpecialist);
    }
}
