<?php

namespace Mapped\Tests;

use Mapped\Factory;
use Mapped\Tests\Fixtures\User\User;

class CallableTest extends \PHPUnit_Framework_TestCase
{
    public function testClosure()
    {
        $factory = new Factory;
        $applied = false;
        $unapplied = false;

        $user = new User('dennis84', 'demo123');

        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ], function ($username, $password) use (&$applied) {
            $applied = true;
        }, function (User $user) use (&$unapplied) {
            $unapplied = true;
        });

        $mapping->unapply($user);

        $mapping->apply([
            'username' => 'foo',
            'password' => 'bar'
        ]);

        $this->assertTrue($applied && $unapplied);
    }

    public function testCallUserFunc()
    {
        $factory = new Factory;
        $user = new User('dennis84', 'demo123');
        $handler = $this->getMock('stdclass', ['apply', 'unapply']);

        $handler->expects($this->once())
            ->method('apply')
            ->with($this->equalTo('foo'), $this->equalTo('bar'));

        $handler->expects($this->once())
            ->method('unapply')
            ->with($this->equalTo($user));

        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ], [$handler, 'apply'], [$handler, 'unapply']);

        $mapping->unapply($user);

        $mapping->apply([
            'username' => 'foo',
            'password' => 'bar'
        ]);
    }
}
