<?php

namespace Mapped\Tests\Constraint;

class BoolTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return [
            [true, true],
            [true, false],
            [false, 'true'],
            [false, 'false'],
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
        $constraint = new \Mapped\Constraint\Bool('');
        $this->assertSame($expected, $constraint->check($value));
    }
}
