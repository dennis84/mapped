<?php

namespace Mapped\Tests\Integration;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\User;

class CallableTest extends \PHPUnit_Framework_TestCase
{
    public function testClosure()
    {
        $m = new Mapped();
        $applied = false;
        $unapplied = false;

        $user = new User('dennis84', 'demo123');

        $mapping = $m->create('', [
            $m->create('username'),
            $m->create('password'),
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
        $m = new Mapped();

        $user = new User('dennis84', 'demo123');

        $handler = $this->getMock(
            'Mapped\Tests\Fixtures\UserHandler',
            ['apply', 'unapply']);

        $handler->expects($this->once())
            ->method('apply')
            ->with($this->equalTo('foo'), $this->equalTo('bar'));

        $handler->expects($this->once())
            ->method('unapply')
            ->with($this->equalTo($user));

        $mapping = $m->create('', [
            $m->create('username'),
            $m->create('password'),
        ], [$handler, 'apply'], [$handler, 'unapply']);

        $mapping->unapply($user);

        $mapping->apply([
            'username' => 'foo',
            'password' => 'bar'
        ]);
    }
}
