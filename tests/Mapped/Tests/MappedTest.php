<?php

namespace Mapped\Tests;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\User;

class MappedTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $m = new Mapped();

        $m->register('Mapped\Tests\Fixtures\User', [
            'username' => $m->mapping(),
            'password' => $m->mapping(),
        ], function ($username, $password) {
            return new User($username, $password);
        }, function (User $user) {
            return [
                'username' => $user->username,
                'password' => $user->password,
            ];
        });

        $user = $m->apply([
            'username' => 'dennis84',
            'password' => 'password',
        ], 'Mapped\Tests\Fixtures\User');

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $user);
        $this->assertSame('dennis84', $user->username);
        $this->assertSame('password', $user->password);

        $result = $m->unapply($user, 'Mapped\Tests\Fixtures\User');
        $this->assertSame([
            'username' => 'dennis84',
            'password' => 'password',
        ], $result);
    }
}
