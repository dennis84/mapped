<?php

namespace Mapped\Tests;

use Mapped\Mapping;
use Mapped\Emitter;

class MappingTest extends \PHPUnit_Framework_TestCase
{
    public function testValidExtensionMethod()
    {
        $mapping = $this->createMapping([
            new \Mapped\Tests\Fixtures\FooExtension]);

        $return = $mapping->foo();
        $this->assertEquals($return, $mapping);
    }

    public function testUndefinedExtensionMethod()
    {
        $this->setExpectedException('BadMethodCallException');
        $mapping = $this->createMapping();
        $mapping->foo();
    }

    public function testSetGetAndHasOption()
    {
        $foo = $this->createMapping();
        $foo->setOption('foo', 'Foo');
        $this->assertSame('Foo', $foo->getOption('foo'));
        $this->assertTrue($foo->hasOption('foo'));
        $this->assertFalse($foo->hasOption('bar'));
    }

    public function testGetOptionFail()
    {
        $this->setExpectedException('InvalidArgumentException');
        $foo = $this->createMapping();
        $foo->getOption('foo');
    }

    private function createMapping(array $extensions = [])
    {
        return new Mapping(new Emitter, $extensions);
    }
}
