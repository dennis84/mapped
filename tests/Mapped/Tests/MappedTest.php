<?php

namespace Mapped\Tests;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Address;

class MappedTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $m = new Mapped();

        $m->register('Mapped\Tests\Fixtures\Address', [
            'city'   => $m->mapping(),
            'street' => $m->mapping(),
        ], function ($city, $street) {
            return new Address($city, $street);
        }, function (Address $address) {
            return [
                'city'   => $address->city,
                'street' => $address->street,
            ];
        });

        $m->register('Mapped\Tests\Fixtures\User', [
            'username' => $m->mapping(),
            'password' => $m->mapping(),
            'address'  => $m->get('Mapped\Tests\Fixtures\Address'),
        ], function ($username, $password, Address $address) {
            return new User($username, $password, $address);
        }, function (User $user) {
            return [
                'username' => $user->username,
                'password' => $user->password,
                'address'  => $user->address,
            ];
        });

        $user = $m->apply([
            'username' => 'dennis84',
            'password' => 'password',
            'address'  => [
                'city'   => 'foo',
                'street' => 'bar',
            ],
        ], 'Mapped\Tests\Fixtures\User');

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $user);
        $this->assertSame('dennis84', $user->username);
        $this->assertSame('password', $user->password);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\Address', $user->address);
        $this->assertSame('foo', $user->address->city);
        $this->assertSame('bar', $user->address->street);

        $result = $m->unapply($user, 'Mapped\Tests\Fixtures\User');
        $this->assertSame([
            'username' => 'dennis84',
            'password' => 'password',
            'address'  => [
                'city'   => 'foo',
                'street' => 'bar',
            ],
        ], $result);
    }
}
