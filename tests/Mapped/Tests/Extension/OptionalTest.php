<?php

namespace Mapped\Tests\Integration;

use Mapped\MappingFactory;
use Mapped\Tests\Fixtures\Address;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\NullToBlahTransformer;

class OptionalTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new MappingFactory();
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
        $factory = new MappingFactory();
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
        $factory = new MappingFactory();
        $mapping = $factory->mapping([
            'foo' => $factory->mapping()->optional()
                ->transform(new NullToBlahTransformer()),
            'bar' => $factory->mapping(),
        ]);

        $result = $mapping->apply(['foo' => null, 'bar' => 'blub']);
        $this->assertSame([
            'foo' => 'blah',
            'bar' => 'blub',
        ], $result);
    }

    private function createNestedMapping()
    {
        $factory = new MappingFactory();

        return $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
            'address'  => $factory->mapping([
                'city'   => $factory->mapping(),
                'street' => $factory->mapping()
            ], function ($city, $street) {
                return new Address($city, $street);
            })->optional(),
        ], function ($username, $password, Address $address = null) {
            return new User($username, $password, $address);
        });
    }
}
