<?php

namespace Mapped\Tests\Integration;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\Address;

class UnapplyIncompleteDataTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $m = new Mapped();

        $mapping = $m->create('', [
            $m->create('username'),
            $m->create('password'),
        ]);

        $data = ['username' => 'dennis84'];
        $result = $mapping->unapply($data);

        $this->assertSame($data, $result);
    }

    public function testB()
    {
        $m = new Mapped();

        $mapping = $m->create('', [
            $m->create('username'),
            $m->create('password'),
            $m->create('address', [
                $m->create('city'),
                $m->create('street'),
            ]),
        ]);

        $data = [
            'username' => 'dennis84',
            'address'  => [
                'street' => 'Foo',
            ],
        ];

        $result = $mapping->unapply($data);
        $this->assertSame($data, $result);
    }
}
