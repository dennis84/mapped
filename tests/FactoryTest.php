<?php

namespace Mapped\Tests\Extension;

use Mapped\Factory;
use Mapped\ValidationException;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testText()
    {
        $factory = new Factory;
        $mapping = $factory->string();
        $this->assertSame('foo', $mapping->apply('foo'));
    }

    public function testTextFail()
    {
        $factory = new Factory;
        $mapping = $factory->string();

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply(true);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.text', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function testNonEmptyText()
    {
        $factory = new Factory;
        $mapping = $factory->string()->notEmpty();
        $this->assertSame('foo', $mapping->apply('foo'));
    }

    public function testNonEmptyTextFail()
    {
        $factory = new Factory;
        $mapping = $factory->string()->notEmpty();

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply('');
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.not_empty', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function testInt()
    {
        $factory = new Factory;

        $mapping = $factory->mapping([
            'int' => $factory->int(),
            'float' => $factory->int(),
        ]);

        $result = $mapping->apply([
            'int' => '12',
            'float' => '42.23',
        ]);

        $this->assertSame(12, $result['int']);
        $this->assertSame(42, $result['float']);
    }

    public function testIntFail()
    {
        $factory = new Factory;
        $mapping = $factory->int();

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply('12a');
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.int', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function testFloat()
    {
        $factory = new Factory;

        $mapping = $factory->mapping([
            'int' => $factory->float(),
            'float' => $factory->float(),
        ]);

        $result = $mapping->apply([
            'int' => '12',
            'float' => '12.23',
        ]);

        $this->assertSame(12.0, $result['int']);
        $this->assertSame(12.23, $result['float']);
    }

    public function testFloatFail()
    {
        $factory = new Factory;
        $mapping = $factory->float();

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply('12a');
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.float', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function testBool()
    {
        $factory = new Factory;

        $mapping = $factory->mapping([
            'a' => $factory->bool(),
            'b' => $factory->bool(),
            'c' => $factory->bool(),
            'd' => $factory->bool(),
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
