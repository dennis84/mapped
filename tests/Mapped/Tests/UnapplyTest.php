<?php

namespace Mapped\Tests\Integration;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Address;
use Mapped\Tests\Fixtures\Location;

class UnapplyTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $m = new Mapped();

        $mapping = $m->mapping([
            'username' => $m->mapping(),
            'password' => $m->mapping(),
        ]);

        $result = $mapping->unapply([
            'username' => 'dennis84',
            'password' => 'password',
        ]);

        $this->assertSame('dennis84', $result['username']);
        $this->assertSame('password', $result['password']);
    }

    public function testB()
    {
        $m = new Mapped();

        $mapping = $m->mapping([
            'username' => $m->mapping(),
            'password' => $m->mapping(),
            'address'  => $m->mapping([
                'city'   => $m->mapping(),
                'street' => $m->mapping(),
            ]),
        ]);

        $result = $mapping->unapply([
            'username' => 'dennis84',
            'password' => 'password',
            'address'  => [
                'city'   => 'Foo',
                'street' => 'Bar',
            ],
        ]);

        $this->assertSame('dennis84', $result['username']);
        $this->assertSame('password', $result['password']);

        $this->assertSame([
            'city'   => 'Foo',
            'street' => 'Bar',
        ], $result['address']);

        $this->assertSame('Foo', $result['address']['city']);
        $this->assertSame('Bar', $result['address']['street']);
    }

    public function testC()
    {
        $m = new Mapped();
        $user = new User('dennis84', 'password');

        $mapping = $m->mapping([
            'username' => $m->mapping(),
            'password' => $m->mapping(),
        ], null, function (User $user) {
            return [
                'username' => $user->username,
                'password' => $user->password
            ];
        });

        $result = $mapping->unapply($user);

        $this->assertSame('dennis84', $result['username']);
        $this->assertSame('password', $result['password']);
    }

    public function testD()
    {
        $m = new Mapped();

        $location = new Location('50', '8');
        $address  = new Address('Foo', 'Bar', $location);
        $user     = new User('dennis84', 'password', $address);

        $mapping = $m->mapping([
            'username' => $m->mapping(),
            'password' => $m->mapping(),
            'address'  => $m->mapping([
                'city'     => $m->mapping(),
                'street'   => $m->mapping(),
                'location' => $m->mapping([
                    'lat' => $m->mapping(),
                    'lng' => $m->mapping(),
                ], null, function (Location $location) {
                    return [
                        'lat' => $location->lat,
                        'lng' => $location->lng,
                    ];
                })
            ], null, function (Address $address) {
                return [
                    'city'     => $address->city,
                    'street'   => $address->street,
                    'location' => $address->location,
                ];
            })
        ], null, function (User $user) {
            return [
                'username' => $user->username,
                'password' => $user->password,
                'address'  => $user->address,
            ];
        });

        $result = $mapping->unapply($user);

        $this->assertSame('dennis84', $result['username']);
        $this->assertSame('password', $result['password']);

        $this->assertSame([
            'username' => 'dennis84',
            'password' => 'password',
            'address'  => [
                'city'     => 'Foo',
                'street'   => 'Bar',
                'location' => ['lat' => '50', 'lng' => '8'],
            ],
        ], $result);
    }
}
