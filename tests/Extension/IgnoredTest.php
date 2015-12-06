<?php

namespace Mapped\Tests\Extension;

use Mapped\Factory;
use Mapped\Tests\Fixtures\User\User;
use Mapped\Tests\Fixtures\User\Address;

class IgnoredTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'id'       => $factory->mapping()->ignored(42),
            'username' => $factory->mapping(),
        ]);

        $result = $mapping->apply([
            'username' => 'dennis',
        ]);

        $this->assertEquals([
            'id' => 42,
            'username' => 'dennis',
        ], $result);
    }

    public function testB()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'id'       => $factory->mapping()->ignored(42),
            'username' => $factory->mapping(),
        ]);

        $result = $mapping->apply([
            'id' => 4,
            'username' => 'dennis',
        ]);

        $this->assertEquals([
            'id' => 42,
            'username' => 'dennis',
        ], $result);
    }

    public function testC()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'id'       => $factory->mapping()->ignored(42),
            'username' => $factory->mapping(),
        ], function ($id, $username) {
            $this->assertSame(42, $id);
            $this->assertSame('dennis', $username);
        });

        $result = $mapping->apply([
            'id' => 4,
            'username' => 'dennis',
        ]);
    }
}
