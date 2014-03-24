<?php

namespace Mapped\Tests\Constraint;

class NumberTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return [
            [true, 1],
            [true, 1.2],
            [true, '1'],
            [true, '1.2'],
            [false, 'foo'],
        ];
    }

    /**
     * @dataProvider validData
     */
    public function testCheck($expected, $value)
    {
        $constraint = new \Mapped\Constraint\Number('');
        $this->assertSame($expected, $constraint->check($value));
    }
}
