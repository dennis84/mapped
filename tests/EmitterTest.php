<?php

namespace Mapped\Tests;

use Mapped\Emitter;

class EmitterTest extends \PHPUnit_Framework_TestCase
{
    public function testOn()
    {
        $emitter = new Emitter;
        $emitter->on('foo', function ($a, $b) {
            $this->assertSame('a', $a);
            $this->assertSame('b', $b);
        });

        $emitter->emit('foo', 'a', 'b');
    }
}
