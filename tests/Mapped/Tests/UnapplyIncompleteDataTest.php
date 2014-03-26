<?php

namespace Mapped\Tests\Integration;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\Address;

class UnapplyIncompleteDataTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $m = new Mapped();

        $mapping = $m->mapping([
            'username' => $m->mapping(),
            'password' => $m->mapping(),
        ]);

        $data = ['username' => 'dennis84'];
        $result = $mapping->unapply($data);

        $this->assertSame($data, $result);
    }

    public function testB()
    {
        $m = new Mapped();

        $mapping = $m->mapping([
            'username' => $m->mapping(),
            'password' => $m->mapping(),
            'address'  => $m->mapping([
                'city'   => $m->mapping(),
                'street' => $m->mapping(),
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
