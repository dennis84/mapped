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

    private function createMapping(array $extensions = [])
    {
        return new Mapping(new Emitter, $extensions);
    }
}
