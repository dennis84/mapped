<?php

namespace Mapped\Tests;

use Mapped\MappingCollectionFactory;
use Mapped\Tests\Fixtures\UserMappingCreator;

class MappingCollectionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $userMapping = new UserMappingCreator;
        $factory = new MappingCollectionFactory;
        $coll = $factory->create([$userMapping]);

        $user = $coll->apply('Mapped\Tests\Fixtures\User', [
            'username' => 'dennis84',
            'password' => 'password',
        ]);

        $this->assertSame('dennis84', $user->username);
        $this->assertSame('password', $user->password);

        $data = $coll->unapply('Mapped\Tests\Fixtures\User', $user);
        $this->assertEquals([
            'username' => 'dennis84',
            'password' => 'password',
        ], $data);
    }
}
