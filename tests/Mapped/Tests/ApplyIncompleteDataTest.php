<?php

namespace Mapped\Tests;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Address;

class ApplyIncompleteDataTest extends \PHPUnit_Framework_TestCase
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
        ]);

        $this->assertSame([
            'username' => 'dennis84',
            'password' => null,
        ], $result);
    }

    public function testB()
    {
        $m = new Mapped();

        $mapping = $m->create('', [
            $m->create('username'),
            $m->create('password'),
        ], function ($username, $password) {
            return new User($username, $password);
        });

        $result = $mapping->apply([
            'username' => 'dennis84',
        ]);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $result);
        $this->assertSame('dennis84', $result->username);
        $this->assertSame(null, $result->password);
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

        $data = [
            'username' => 'dennis84',
            'password' => 'password',
        ];

        $result = $mapping->apply($data);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\Address', $result->address);
        $this->assertSame(null, $result->address->city);
        $this->assertSame(null, $result->address->street);
    }
}
