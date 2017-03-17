<?php

use App\Research\Domain\Entities\Specialist;
use App\Research\Domain\ValueObjects\SpecialistIdentifer;
use App\Research\Domain\ValueObjects\SpecialistStatus;

/**
 * @coversDefaultClass \App\Research\Domain\Entities\Specialist
 */
class SpecialistTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::prospect
     * @covers ::getId
     * @covers ::getStatus
     * @covers ::isAcceptedTerms
     */
    public function testProspect()
    {
        $specialist = Specialist::prospect();

        $this->assertFalse($specialist->isAcceptedTerms());
        $this->assertInstanceOf(SpecialistIdentifer::class, $specialist->getId());
        $this->assertSame(SpecialistStatus::PROSPECT, (string) $specialist->getStatus());
    }

    /**
     * @covers ::approve
     */
    public function testApprove()
    {
        $specialist = Specialist::prospect();
        $specialist->approve();

        $this->assertSame(SpecialistStatus::APPROVED, (string) $specialist->getStatus());
    }

    /**
     * @covers ::approve
     */
    public function testApproveThrowsExceptionWhenNotProspect()
    {
        $specialist = Specialist::prospect();
        $specialist->approve();

        $this->expectException(RuntimeException::class);

        $specialist->approve();
    }

    /**
     * @covers ::discard
     */
    public function testDiscard()
    {
        $specialist = Specialist::prospect();
        $specialist->discard();

        $this->assertSame(SpecialistStatus::DISCARDED, (string) $specialist->getStatus());
    }

    /**
     * @covers ::discard
     */
    public function testDiscardThrowsExceptionWhenAlreadyApproved()
    {
        $specialist = Specialist::prospect();
        $specialist->approve();

        $this->expectException(RuntimeException::class);

        $specialist->discard();
    }

    /**
     * @covers ::discard
     */
    public function testDiscardThrowsExceptionWhenInterested()
    {
        $specialist = Specialist::prospect();
        $specialist->approve();
        $specialist->interested();

        $this->expectException(RuntimeException::class);

        $specialist->discard();
    }


    /**
     * @covers ::discard
     */
    public function testDiscardThrowsExceptionWhenAlreadyDiscarded()
    {
        $specialist = Specialist::prospect();
        $specialist->discard();

        $this->expectException(RuntimeException::class);

        $specialist->discard();
    }

    /**
     * @covers ::interested
     */
    public function testInterested()
    {
        $specialist = Specialist::prospect();
        $specialist->approve();
        $specialist->interested();

        $this->assertSame(SpecialistStatus::INTERESTED, (string) $specialist->getStatus());
    }

    /**
     * @covers ::interested
     */
    public function testInterestedThrowsExceptionWhenProspect()
    {
        $specialist = Specialist::prospect();

        $this->expectException(RuntimeException::class);

        $specialist->interested();
    }

    /**
     * @covers ::interested
     */
    public function testInterestedThrowsExceptionWhenDiscarded()
    {
        $specialist = Specialist::prospect();
        $specialist->discard();

        $this->expectException(RuntimeException::class);

        $specialist->interested();
    }

    /**
     * @covers ::interested
     */
    public function testInterestedThrowsExceptionWhenAlreadyInterested()
    {
        $specialist = Specialist::prospect();
        $specialist->approve();
        $specialist->interested();

        $this->expectException(RuntimeException::class);

        $specialist->interested();
    }

    /**
     * @covers ::interested
     */
    public function testInterestedThrowsExceptionWhenNotInterested()
    {
        $specialist = Specialist::prospect();
        $specialist->approve();
        $specialist->notInterested();

        $this->expectException(RuntimeException::class);

        $specialist->interested();
    }

    /**
     * @covers ::notInterested
     */
    public function testNotInterested()
    {
        $specialist = Specialist::prospect();
        $specialist->approve();
        $specialist->notInterested();

        $this->assertSame(SpecialistStatus::NOT_INTERESTED, (string) $specialist->getStatus());
    }

    /**
     * @covers ::notInterested
     */
    public function testNotInterestedThrowsExceptionWhenProspect()
    {
        $specialist = Specialist::prospect();

        $this->expectException(RuntimeException::class);

        $specialist->notInterested();
    }

    /**
     * @covers ::notInterested
     */
    public function testNotInterestedThrowsExceptionWhenDiscarded()
    {
        $specialist = Specialist::prospect();
        $specialist->discard();

        $this->expectException(RuntimeException::class);

        $specialist->notInterested();
    }

    /**
     * @covers ::notInterested
     */
    public function testNotInterestedThrowsExceptionWhenAlreadyInterested()
    {
        $specialist = Specialist::prospect();
        $specialist->approve();
        $specialist->interested();

        $this->expectException(RuntimeException::class);

        $specialist->notInterested();
    }

    /**
     * @covers ::notInterested
     */
    public function testNotInterestedThrowsExceptionWhenInterested()
    {
        $specialist = Specialist::prospect();
        $specialist->approve();
        $specialist->interested();

        $this->expectException(RuntimeException::class);

        $specialist->notInterested();
    }
}