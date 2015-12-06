<?php

namespace Mapped\Tests;

use Mapped\Factory;
use Mapped\ValidationException;
use Mapped\Tests\Fixtures\User\User;
use Mapped\Tests\Fixtures\User\Address;

class ApplyTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ]);

        $result = $mapping->apply([
            'username' => 'dennis',
            'password' => 'password',
        ]);

        $this->assertEquals([
            'username' => 'dennis',
            'password' => 'password',
        ], $result);
    }

    public function testB()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ]);

        $result = $mapping->apply([
            'username' => 'dennis',
            'password' => null,
        ]);

        $this->assertEquals([
            'username' => 'dennis',
            'password' => null,
        ], $result);
    }

    public function testC()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ], function ($username, $password) {
            return new User($username, $password);
        });

        $result = $mapping->apply([
            'username' => 'dennis',
            'password' => 'password',
        ]);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User\User', $result);
        $this->assertSame('dennis', $result->username);
        $this->assertSame('password', $result->password);
    }

    public function testD()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ], function ($username, $password) {
            return new User($username, $password);
        });

        $result = $mapping->apply([
            'username' => 'dennis',
            'password' => null,
        ]);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User\User', $result);
        $this->assertSame('dennis', $result->username);
        $this->assertNull($result->password);
    }

    public function testE()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ], function ($username, $password) {
            $this->fail();
        });

        $this->setExpectedException('Mapped\ValidationException');
        $result = $mapping->apply(['username' => 'dennis']);

        try {
            $result = $mapping->apply(['username' => 'dennis']);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('error.required', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function testF()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
            'address'  => $factory->mapping([
                'city'   => $factory->mapping(),
                'street' => $factory->mapping(),
            ], function ($city, $street) {
                return new Address($city, $street);
            }),
        ], function ($username, $password, Address $address) {
            return new User($username, $password, $address);
        });

        $result = $mapping->apply([
            'username' => 'dennis84',
            'password' => 'password',
            'address' => [
                'city'   => 'Foo',
                'street' => 'Bar',
            ],
        ]);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User\User', $result);
        $this->assertSame('dennis84', $result->username);
        $this->assertSame('password', $result->password);
        $this->assertInstanceOf('Mapped\Tests\Fixtures\User\Address', $result->address);
        $this->assertSame('Foo', $result->address->city);
        $this->assertSame('Bar', $result->address->street);
    }
}
