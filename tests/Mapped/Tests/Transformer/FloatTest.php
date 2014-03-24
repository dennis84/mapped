<?php

namespace Mapped\Tests;

class FloatTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return [
            [42.2, 42.2],
            [42.0, 42],
            [42.2, '42.2'],
            [42.0, '42'],

            // do not transform invalid values
            // @todo or throw an exception here?
            ['42a', '42a'],
        ];
    }

    /**
     * @dataProvider validData
     */
    public function testTransform($expected, $value)
    {
        $transformer = new \Mapped\Transformer\Float();
        $this->assertSame($expected, $transformer->transform($value));
    }
}
