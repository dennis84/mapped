<?php

namespace Mapped\Tests;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Address;

class ApplyValidDataTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $m = new Mapped();
        $mapping = $m->mapping([
            'username' => $m->mapping(),
            'password' => $m->mapping(),
        ]);

        $result = $mapping->apply([
            'username' => 'dennis84',
            'password' => 'password',
        ]);

        $this->assertSame([
            'username' => 'dennis84',
            'password' => 'password',
        ], $result);
    }

    public function testB()
    {
        $m = new Mapped();
        $mapping = $m->mapping([
            'username' => $m->mapping(),
            'password' => $m->mapping(),
        ], function ($username, $password) {
            return new User($username, $password);
        });

        $result = $mapping->apply([
            'username' => 'dennis84',
            'password' => 'password',
        ], 'Mapped\Tests\Fixtures\User');

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $result);
        $this->assertSame('dennis84', $result->username);
        $this->assertSame('password', $result->password);
    }

    public function testC()
    {
        $m = new Mapped();
        $mapping = $m->mapping([
            'username' => $m->mapping(),
            'password' => $m->mapping(),
            'address'  => $m->mapping([
                'city'   => $m->mapping(),
                'street' => $m->mapping(),
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

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $result);
        $this->assertSame('dennis84', $result->username);
        $this->assertSame('password', $result->password);
        $this->assertInstanceOf('Mapped\Tests\Fixtures\Address', $result->address);
        $this->assertSame('Foo', $result->address->city);
        $this->assertSame('Bar', $result->address->street);
    }
}
