<?php

namespace Mapped\Tests\Transformer;

class IntTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return [
            [42, 42.2],
            [42, 42],
            [42, '42.2'],
            [42, '42'],
            ['42a', '42a'],
        ];
    }

    /**
     * @dataProvider validData
     */
    public function testTransform($expected, $value)
    {
        $transformer = new \Mapped\Transformer\IntTransformer;
        $this->assertSame($expected, $transformer->transform($value));
    }
}
