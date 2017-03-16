<?php

namespace ValueObjects;

use App\ValueObjects\ValueObject;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \App\ValueObjects\ValueObject
 */
class ValueObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::__toString
     */
    public function testValueObject()
    {
        $expected = 'an object value';

        $test = new StubValueObject($expected);

        $this->assertSame($expected, (string) $test);
    }

    /**
     * @covers ::is
     */
    public function testIs()
    {
        $expected = 'a value';

        $test = new StubValueObject($expected);

        $this->assertTrue($test->is($expected));
        $this->assertFalse($test->is('another value'));
    }
    /**
     * @covers ::isNot
     */
    public function testIsNot()
    {
        $expected = 'a value';

        $test = new StubValueObject($expected);

        $this->assertTrue($test->isNot('another value'));
        $this->assertFalse($test->isNot($expected));
    }
}

class StubValueObject extends ValueObject { }