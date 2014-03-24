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
        $mapping = $m->create('', [
            $m->create('username'),
            $m->create('password'),
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
        $mapping = $m->create('Mapped\Tests\Fixtures\User', [
            $m->create('username'),
            $m->create('password'),
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
        $mapping = $m->create('', [
            $m->create('username'),
            $m->create('password'),
            $m->create('address', [
                $m->create('city'),
                $m->create('street'),
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
