<?php

namespace ValueObjects;

use App\Research\Domain\ValueObjects\SpecialistStatus;
use PHPUnit_Framework_TestCase;
use RuntimeException;

/**
 * @coversDefaultClass \App\Research\Domain\ValueObjects\SpecialistStatus
 */
class SpecialistStatusTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideSpecialistStatuses
     * @covers ::__construct
     */
    public function testWithValidValue($expected)
    {
        $status = new SpecialistStatus($expected);

        $this->assertSame($expected, (string) $status);
    }

    public function provideSpecialistStatuses()
    {
        return array_chunk([
            SpecialistStatus::PROSPECT,
            SpecialistStatus::INTERESTED,
            SpecialistStatus::NOT_INTERESTED,
            SpecialistStatus::APPROVED,
            SpecialistStatus::DISCARDED
        ], 1);
    }

    public function testWithInvalidValueThrowsException()
    {
        $this->expectException(RuntimeException::class);

        new SpecialistStatus('some random value');
    }
}