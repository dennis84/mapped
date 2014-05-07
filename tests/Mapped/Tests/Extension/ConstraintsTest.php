<?php

namespace Mapped\Tests\Extension;

use Mapped\MappingFactory;
use Mapped\ValidationException;

class ConstraintsTest extends \PHPUnit_Framework_TestCase
{
    public function test_nonEmptyText_fail()
    {
        $factory = new MappingFactory();

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

    public function test_nonEmptyText_with_nothing()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'username' => $factory->mapping()->nonEmptyText()
        ]);

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply([]);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.non_empty_text', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function test_integer_fail()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'integer' => $factory->mapping()->integer()
        ]);

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply(['integer' => '12a']);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.integer', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function test_integer_pass()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'integer' => $factory->mapping()->integer(),
            'float'   => $factory->mapping()->integer(),
        ]);

        $result = $mapping->apply([
            'integer' => '12',
            'float'   => '42.23',
        ]);

        $this->assertSame(12, $result['integer']);
        $this->assertSame(42, $result['float']);
    }

    public function test_number_fail()
    {
        $factory = new MappingFactory();

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

    public function test_number_pass()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'integer' => $factory->mapping()->float(),
            'float'   => $factory->mapping()->float(),
        ]);

        $result = $mapping->apply([
            'integer' => '12',
            'float'   => '12.23',
        ]);

        $this->assertSame(12.0, $result['integer']);
        $this->assertSame(12.23, $result['float']);
    }

    public function test_boolean()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'a' => $factory->mapping()->boolean(),
            'b' => $factory->mapping()->boolean(),
            'c' => $factory->mapping()->boolean(),
            'd' => $factory->mapping()->boolean(),
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

    public function test_required_boolean()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'accept' => $factory->mapping()->required()->boolean(),
        ]);

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply([]);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.required', $errors[0]->getMessage());
            $this->assertSame('error.boolean', $errors[1]->getMessage());
            throw $e;
        }
    }
}
