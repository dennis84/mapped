<?php

namespace Mapped\Tests\Integration;

use Mapped\Mapped;

class CustomConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $m = new Mapped();

        $mapping = $m->create('', [
            $m->create('username')
                ->verifying('Username taken.', function ($username) {
                    return 'dennis84' !== $username;
                })
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $mapping->apply(['username' => 'dennis84']);
    }

    public function testB()
    {
        $m = new Mapped();

        $mapping = $m->create('', [
            $m->create('username'),
            $m->create('password'),
            $m->create('password2'),
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
}
