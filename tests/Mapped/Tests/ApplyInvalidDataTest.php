<?php

namespace Mapped\Tests\Integration;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\User;

class ApplyInvalidDataTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $m = new Mapped();

        $mapping = $m->mapping([
            'username' => $m->mapping(),
            'password' => $m->mapping(),
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
        $m = new Mapped();

        $mapping = $m->mapping([
            'username' => $m->mapping(),
            'password' => $m->mapping(),
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
