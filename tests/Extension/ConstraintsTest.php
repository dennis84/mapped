<?php

namespace Mapped\Tests\Extension;

use Mapped\MappingFactory;
use Mapped\ValidationException;

class ConstraintsTest extends \PHPUnit_Framework_TestCase
{
    public function testNonEmptyTextWithEmptyString()
    {
        $factory = new MappingFactory;

        $mapping = $factory->mapping([
            'username' => $factory->mapping()->nonEmptyText(),
        ]);

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply(['username' => '']);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.non_empty_text', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function testNonEmptyTextWithNothing()
    {
        $factory = new MappingFactory;

        $mapping = $factory->mapping([
            'username' => $factory->mapping()->nonEmptyText()
        ]);

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply([]);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.required', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function testIntFail()
    {
        $factory = new MappingFactory;

        $mapping = $factory->mapping([
            'int' => $factory->mapping()->int()
        ]);

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply(['int' => '12a']);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.int', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function testInt()
    {
        $factory = new MappingFactory;

        $mapping = $factory->mapping([
            'int' => $factory->mapping()->int(),
            'float'   => $factory->mapping()->int(),
        ]);

        $result = $mapping->apply([
            'int' => '12',
            'float'   => '42.23',
        ]);

        $this->assertSame(12, $result['int']);
        $this->assertSame(42, $result['float']);
    }

    public function testFloatFail()
    {
        $factory = new MappingFactory;

        $mapping = $factory->mapping([
            'float' => $factory->mapping()->float()
        ]);

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply(['float' => '12a']);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.float', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function testFloat()
    {
        $factory = new MappingFactory;

        $mapping = $factory->mapping([
            'int' => $factory->mapping()->float(),
            'float'   => $factory->mapping()->float(),
        ]);

        $result = $mapping->apply([
            'int' => '12',
            'float'   => '12.23',
        ]);

        $this->assertSame(12.0, $result['int']);
        $this->assertSame(12.23, $result['float']);
    }

    public function testBool()
    {
        $factory = new MappingFactory;

        $mapping = $factory->mapping([
            'a' => $factory->mapping()->bool(),
            'b' => $factory->mapping()->bool(),
            'c' => $factory->mapping()->bool(),
            'd' => $factory->mapping()->bool(),
        ]);

        $result = $mapping->apply([
            'a' => true,
            'b' => false,
            'c' => 'true',
            'd' => 'false',
        ]);

        $this->assertTrue($result['a']);
        $this->assertFalse($result['b']);
        $this->assertTrue($result['c']);
        $this->assertFalse($result['d']);
    }
}
