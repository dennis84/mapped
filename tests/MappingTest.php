<?php

namespace Mapped\Tests;

use Mapped\Mapping;
use Mapped\Emitter;

class MappingTest extends \PHPUnit_Framework_TestCase
{
    public function testValidExtensionMethod()
    {
        $extension = $this->getMock('Mapped\ExtensionInterface', ['foo']);
        $extension->expects($this->once())->method('foo');
        $mapping = new Mapping(new Emitter, [$extension]);
        $return = $mapping->foo();
    }

    public function testUndefinedExtensionMethod()
    {
        $this->setExpectedException('BadMethodCallException');
        $mapping = new Mapping(new Emitter);
        $mapping->foo();
    }
}
