<?php

namespace Mapped\Tests\Integration;

use Mapped\MappingFactory;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Address;
use Mapped\Tests\Fixtures\Location;

class UnapplyTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
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
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
            'address'  => $factory->mapping([
                'city'   => $factory->mapping(),
                'street' => $factory->mapping(),
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
        $factory = new MappingFactory();
        $user = new User('dennis84', 'password');

        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
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
        $factory = new MappingFactory();

        $location = new Location('50', '8');
        $address  = new Address('Foo', 'Bar', $location);
        $user     = new User('dennis84', 'password', $address);

        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
            'address'  => $factory->mapping([
                'city'     => $factory->mapping(),
                'street'   => $factory->mapping(),
                'location' => $factory->mapping([
                    'lat' => $factory->mapping(),
                    'lng' => $factory->mapping(),
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
