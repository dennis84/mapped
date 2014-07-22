<?php

namespace Mapped\Tests;

use Mapped\MappingCollection;
use Mapped\MappingFactory;
use Mapped\Transformer\Callback;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Address;

class MappingCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new MappingFactory;
        $coll = new MappingCollection;

        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ], function ($username, $password) {
            return new User($username, $password);
        }, function (User $user) {
            return [
                'username' => $user->username,
                'password' => $user->password,
            ];
        });

        $coll->add('Mapped\Tests\Fixtures\User', $mapping);

        $user = $coll->apply([
            'username' => 'dennis84',
            'password' => 'password',
        ], 'Mapped\Tests\Fixtures\User');

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $user);
        $this->assertSame('dennis84', $user->username);
        $this->assertSame('password', $user->password);

        $result = $coll->unapply($user, 'Mapped\Tests\Fixtures\User');
        $this->assertSame([
            'username' => 'dennis84',
            'password' => 'password',
        ], $result);
    }

    public function testB()
    {
        $factory = new MappingFactory;
        $coll = new MappingCollection;

        $addressMapping = $factory->mapping([
            'city'   => $factory->mapping(),
            'street' => $factory->mapping(),
        ], function ($city, $street) {
            return new Address($city, $street);
        });

        $coll->add('Mapped\Tests\Fixtures\Address', $addressMapping);

        $userMapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
            'address'  => $coll->get('Mapped\Tests\Fixtures\Address'),
        ], function ($username, $password, Address $address) {
            return new User($username, $password, $address);
        });

        $coll->add('Mapped\Tests\Fixtures\User', $userMapping);

        $user = $coll->apply([
            'username' => 'dennis84',
            'password' => 'password',
            'address'  => [
                'city'   => 'foo',
                'street' => 'bar',
            ]
        ], 'Mapped\Tests\Fixtures\User');

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $user);
        $this->assertSame('dennis84', $user->username);
        $this->assertSame('password', $user->password);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\Address', $user->address);
        $this->assertSame('foo', $user->address->city);
        $this->assertSame('bar', $user->address->street);
    }

    public function testC()
    {
        $factory = new MappingFactory;
        $coll = new MappingCollection;

        $foo = $factory->mapping();
        $coll->add('foo', $foo);

        $result1 = $coll->apply('test', 'foo');

        $coll->get('foo')->transform(new Callback(function () {
            return;
        }));

        $result2 = $coll->apply('test', 'foo');

        $this->assertNotSame($result1, $result2);
    }

    public function testD()
    {
        $this->setExpectedException('InvalidArgumentException');
        $coll = new MappingCollection;
        $coll->get('foo');
    }
}
