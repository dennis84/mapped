<?php

namespace Mapped;

use Mapped\Transformer;

class TransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $transformer = new Transformer;
        $this->assertSame('foo', $transformer->transform('foo'));
    }

    public function testReverseTransform()
    {
        $transformer = new Transformer;
        $this->assertSame('foo', $transformer->reverseTransform('foo'));
    }
}
