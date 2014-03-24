<?php

namespace Mapped\Tests;

class IntegerTest extends \PHPUnit_Framework_TestCase
{
    public function validData()
    {
        return [
            [42, 42.2],
            [42, 42],
            [42, '42.2'],
            [42, '42'],

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
        $transformer = new \Mapped\Transformer\Integer();
        $this->assertSame($expected, $transformer->transform($value));
    }
}
