<?php

namespace Mapped\Tests\Integration;

use Mapped\MappingFactory;
use Mapped\Tests\Fixtures\User;

class CustomConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new MappingFactory;

        $mapping = $factory->mapping([
            'username' => $factory->mapping()
                ->verifying('Username taken.', function ($username) {
                    return 'dennis84' !== $username;
                })
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $mapping->apply(['username' => 'dennis84']);
    }

    public function testB()
    {
        $factory = new MappingFactory;

        $mapping = $factory->mapping([
            'username'  => $factory->mapping(),
            'password'  => $factory->mapping(),
            'password2' => $factory->mapping(),
        ])->verifying('Invalid password or username.', function ($username, $password, $password2) {
            return $password === $password2;
        })->verifying('Username taken.', function ($username, $password, $password2) {
            return 'dennis84' !== $username;
        });

        $this->setExpectedException('Mapped\ValidationException');
        $mapping->apply([
            'username'  => 'dennis84',
            'password'  => 'password',
            'password2' => 'demo',
        ]);
    }

    public function testC()
    {
        $factory = new MappingFactory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ], function ($username, $password) {
            return new User($username, $password);
        })->verifying('foo', function ($value) {
            $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $value);
            return true;
        });

        $mapping->apply([
            'username' => 'dennis',
            'password' => 'password',
        ]);
    }
}
