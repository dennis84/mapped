<?php

namespace Mapped\Tests\Extension;

use Mapped\Mapped;

class ConstraintsTest extends \PHPUnit_Framework_TestCase
{
    public function test_nonEmptyText_fail()
    {
        $m = new Mapped();

        $mapping = $m->mapping([
            'username' => $m->mapping()->nonEmptyText(),
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $mapping->apply(['username' => '']);
    }

    public function test_nonEmptyText_with_nothing()
    {
        $m = new Mapped();

        $mapping = $m->mapping([
            'username' => $m->mapping()->nonEmptyText()
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $mapping->apply([]);
    }

    public function test_integer_fail()
    {
        $m = new Mapped();

        $mapping = $m->mapping([
            'integer' => $m->mapping()->integer()
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $mapping->apply(['integer' => '12a']);
    }

    public function test_integer_pass()
    {
        $m = new Mapped();

        $mapping = $m->mapping([
            'integer' => $m->mapping()->integer(),
            'float'   => $m->mapping()->integer(),
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
        $m = new Mapped();

        $mapping = $m->mapping([
            'float' => $m->mapping()->float()
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $mapping->apply(['float' => '12a']);
    }

    public function test_number_pass()
    {
        $m = new Mapped();

        $mapping = $m->mapping([
            'integer' => $m->mapping()->float(),
            'float'   => $m->mapping()->float(),
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
        $m = new Mapped();

        $mapping = $m->mapping([
            'a' => $m->mapping()->boolean(),
            'b' => $m->mapping()->boolean(),
            'c' => $m->mapping()->boolean(),
            'd' => $m->mapping()->boolean(),
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
        $m = new Mapped();

        $mapping = $m->mapping([
            'accept' => $m->mapping()->required()->boolean(),
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $mapping->apply([]);
    }
}
