<?php

namespace Mapped\Tests\Constraint;

class BooleanTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return [
            [true, true],
            [true, 'true'],
            [true, false],
            [true, 'false'],
            [false, 1],
            [false, '1'],
            [false, '0'],
            [false, 'a'],
        ];
    }

    /**
     * @dataProvider validData
     */
    public function testCheck($expected, $value)
    {
        $constraint = new \Mapped\Constraint\Boolean('');
        $this->assertSame($expected, $constraint->check($value));
    }
}
