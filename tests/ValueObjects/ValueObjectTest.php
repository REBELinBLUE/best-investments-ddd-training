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

        $valueObject = new StubValueObject($expected);

        $this->assertSame($expected, (string) $valueObject);
    }

    /**
     * @covers ::is
     */
    public function testIs()
    {
        $expected = 'a value';

        $valueObject = new StubValueObject($expected);

        $this->assertTrue($valueObject->is($expected));
        $this->assertFalse($valueObject->is('another value'));
    }
    /**
     * @covers ::isNot
     */
    public function testIsNot()
    {
        $expected = 'a value';

        $valueObject = new StubValueObject($expected);

        $this->assertTrue($valueObject->isNot('another value'));
        $this->assertFalse($valueObject->isNot($expected));
    }
}

class StubValueObject extends ValueObject { }