<?php

namespace Mapped\Tests\Constraint;

class RequiredTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return [
            [true, 'foo'],
            [true, 1],
            [true, 0],
            [true, []],
            [false, ''],
            [false, null],
        ];
    }

    /**
     * @dataProvider validData
     */
    public function testCheck($expected, $value)
    {
        $constraint = new \Mapped\Constraint\Required('');
        $this->assertSame($expected, $constraint->check($value));
    }
}
