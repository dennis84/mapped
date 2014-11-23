<?php

namespace Mapped\Tests\Transformer;

class BoolTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return [
            [true, true],
            [true, 'true'],
            [false, false],
            [false, 'false'],
        ];
    }

    /**
     * @dataProvider validData
     */
    public function testTransform($expected, $value)
    {
        $transformer = new \Mapped\Transformer\Bool;
        $this->assertSame($expected, $transformer->transform($value));
    }
}
