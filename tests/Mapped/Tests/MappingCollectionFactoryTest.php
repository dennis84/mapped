<?php

namespace Mapped\Tests;

use Mapped\MappingCollectionFactory;
use Mapped\MappingFactory;
use Mapped\Tests\Fixtures\UserMappingCreator;

class MappingCollectionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $userMapping = new UserMappingCreator();

        $factory = new MappingCollectionFactory();
        $coll = $factory->create([$userMapping]);

        $user = $coll->apply([
            'username' => 'dennis84',
            'password' => 'password',
        ], 'Mapped\Tests\Fixtures\User');

        $this->assertSame('dennis84', $user->username);
        $this->assertSame('password', $user->password);

        $data = $coll->unapply($user, 'Mapped\Tests\Fixtures\User');
        $this->assertEquals([
            'username' => 'dennis84',
            'password' => 'password',
        ], $data);
    }
}
