<?php

namespace Mapped\Tests\Integration;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\Address;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\NullToBlahTransformer;

class OptionalTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $m = new Mapped();
        $mapping = $this->createNestedMapping();
        $data = [
            'username' => 'dennis84',
            'password' => 'password',
            'address'  => [
                'city'   => 'foo',
                'street' => 'bar',
            ],
        ];

        $result = $mapping->apply($data);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $result);
        $this->assertSame('dennis84', $result->username);
        $this->assertSame('password', $result->password);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\Address', $result->address);
        $this->assertSame('foo', $result->address->city);
        $this->assertSame('bar', $result->address->street);
    }

    public function testB()
    {
        $m = new Mapped();
        $mapping = $this->createNestedMapping();
        $data = [
            'username' => 'dennis84',
            'password' => 'password',
        ];

        $result = $mapping->apply($data);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $result);
        $this->assertSame('dennis84', $result->username);
        $this->assertSame('password', $result->password);
        $this->assertNull($result->address);
    }

    public function testC()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('foo')->optional()
                ->transform(new NullToBlahTransformer()),
            $m->create('bar'),
        ]);

        $result = $mapping->apply(['foo' => null, 'bar' => 'blub']);
        $this->assertSame([
            'foo' => 'blah',
            'bar' => 'blub',
        ], $result);
    }

    private function createNestedMapping()
    {
        $m = new Mapped();

        return $m->create('', [
            $m->create('username'),
            $m->create('password'),
            $m->create('address', [
                $m->create('city'),
                $m->create('street')
            ], function ($city, $street) {
                return new Address($city, $street);
            })->optional(),
        ], function ($username, $password, Address $address = null) {
            return new User($username, $password, $address);
        });
    }
}
