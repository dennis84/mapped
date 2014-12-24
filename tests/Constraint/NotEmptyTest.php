<?php

namespace Mapped\Tests\Constraint;

class NotEmptyTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return [
            [true, 'foo'],
            [true, "\n"],
            [true, ' '],
            [false, ''],
            [true, 0],
            [true, 1],
            [false, null],
            [false, []],
            [true, [1]],
        ];
    }

    /**
     * @dataProvider validData
     */
    public function testCheck($expected, $value)
    {
        $constraint = new \Mapped\Constraint\NotEmpty('');
        $this->assertSame($expected, $constraint->check($value));
    }
}
