<?php

namespace Mapped\Tests\Constraint;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return [
            ['string', true, 'foo'],
            ['string', false, true],
            ['string', null, null],
            ['int', true, 1],
            ['int', false, '1'],
            ['float', true, 1.0],
            ['float', false, 1],
            ['bool', true, true],
            ['bool', true, false],
            ['bool', false, 1],
            ['foo', false, 1],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testCheck($type, $expected, $value)
    {
        $constraint = new \Mapped\Constraint\Type('', $type);
        $this->assertSame($expected, $constraint->check($value));
    }
}
