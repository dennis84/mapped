<?php

namespace Mapped\Tests\Extension;

use Mapped\Factory;
use Mapped\Tests\Fixtures\User\User;
use Mapped\Tests\Fixtures\User\Address;

class OptionalTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping()->optional(),
        ]);

        $result = $mapping->apply([
            'username' => 'dennis',
        ]);

        $this->assertEquals([
            'username' => 'dennis',
            'password' => null,
        ], $result);
    }

    public function testB()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping()->optional(),
        ]);

        $result = $mapping->apply([
            'username' => 'dennis',
            'password' => 'passwd',
        ]);

        $this->assertEquals([
            'username' => 'dennis',
            'password' => 'passwd',
        ], $result);
    }

    public function testC()
    {
        $factory = new Factory;
        $mapping = $this->createNestedMapping();
        $data = [
            'username' => 'dennis84',
            'password' => 'password',
        ];

        $result = $mapping->apply($data);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User\User', $result);
        $this->assertSame('dennis84', $result->username);
        $this->assertSame('password', $result->password);
        $this->assertNull($result->address);
    }

    public function testD()
    {
        $transformerA = $this->getMock('Mapped\Transformer');
        $transformerA->expects($this->at(0))
            ->method('transform')->with('foo')
            ->will($this->returnValue('blah'));

        $transformerB = $this->getMock('Mapped\Transformer');
        $transformerB->expects($this->at(0))
            ->method('transform')->with(null);

        $factory = new Factory;
        $mapping = $factory->mapping([
            'foo' => $factory->mapping()->optional()
                ->transform($transformerA),
            'bar' => $factory->mapping()->optional()
                ->transform($transformerB),
        ]);

        $result = $mapping->apply(['foo' => 'foo']);
        $this->assertSame([
            'foo' => 'blah',
            'bar' => null,
        ], $result);
    }

    private function createNestedMapping()
    {
        $factory = new Factory;

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
