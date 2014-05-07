<?php

namespace Mapped\Tests\Integration;

use Mapped\MappingFactory;
use Mapped\Tests\Fixtures\User;

class ApplyInvalidDataTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ]);

        $result = $mapping->apply([
            'foo' => [
                'username' => 'dennis84',
                'password' => 'password',
            ],
        ]);

        $this->assertSame(null, $result['username']);
        $this->assertSame(null, $result['password']);
    }

    public function testB()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ], function ($username, $password) {
            return new User($username, $password);
        });

        $result = $mapping->apply([
            'foo' => [
                'username' => 'dennis84',
                'password' => 'password',
            ],
        ]);

        $this->assertSame(null, $result->username);
        $this->assertSame(null, $result->password);
    }
}
