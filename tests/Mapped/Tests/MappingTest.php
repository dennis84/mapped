<?php

namespace Mapped\Tests;

use Mapped\MappingFactory;
use Mapped\Mapping;

class MappingTest extends MappedTestCase
{
    public function testInitialize()
    {
        $ext = $this->getMock('Mapped\Extension');
        $ext->expects($this->once())
            ->method('initialize');

        $mapping = $this->createMapping([$ext]);
    }

    public function testInitializeMethodMustNotBeCallable()
    {
        $ext = $this->getMock('Mapped\Extension');
        $ext->expects($this->once())
            ->method('initialize');

        $mapping = $this->createMapping([$ext]);
        $mapping->initialize($mapping);
    }

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

    public function testGetTransformers()
    {
        $a = new \Mapped\Transformer\Integer;
        $b = new \Mapped\Transformer\Float;
        $c = new \Mapped\Transformer\Boolean;

        $foo = $this->createMapping();
        $foo->transform($a);
        $foo->transform($b);
        $foo->transform($c);

        $transformers = $foo->getTransformers();
        $this->assertEquals($a, $transformers[0]);
        $this->assertEquals($b, $transformers[1]);
        $this->assertEquals($c, $transformers[2]);

        $foo = $this->createMapping();
        $foo->transform($a, 0);
        $foo->transform($b, 2);
        $foo->transform($c, 1);

        $transformers = $foo->getTransformers();
        $this->assertEquals($b, $transformers[0]);
        $this->assertEquals($c, $transformers[1]);
        $this->assertEquals($a, $transformers[2]);
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
}
