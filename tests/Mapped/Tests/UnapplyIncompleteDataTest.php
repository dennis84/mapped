<?php

namespace Mapped\Tests\Integration;

use Mapped\MappingFactory;
use Mapped\Tests\Fixtures\Address;

class UnapplyIncompleteDataTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ]);

        $data = ['username' => 'dennis84'];
        $result = $mapping->unapply($data);

        $this->assertSame($data, $result);
    }

    public function testB()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
            'address'  => $factory->mapping([
                'city'   => $factory->mapping(),
                'street' => $factory->mapping(),
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
