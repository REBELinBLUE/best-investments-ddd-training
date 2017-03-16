<?php

namespace ValueObjects;

use App\ValueObjects\ProjectStatus;
use PHPUnit_Framework_TestCase;
use \RuntimeException;

/**
 * @coversDefaultClass \App\ValueObjects\ProjectStatus
 */
class ProjectStatusTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideProjectStatuses
     * @covers ::__construct
     */
    public function testWithValidValue($expected)
    {
        $status = new ProjectStatus($expected);

        $this->assertSame($expected, (string) $status);
    }

    public function provideProjectStatuses()
    {
        return array_chunk([
           ProjectStatus::DRAFT, ProjectStatus::STARTED
        ], 1);
    }

    public function testWithInvalidValueThrowsException()
    {
        $this->expectException(RuntimeException::class);

        new ProjectStatus('some random value');
    }
}