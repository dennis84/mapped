<?php

namespace Mapped\Tests\Fixtures;

use Mapped\MappingCreatorInterface;
use Mapped\MappingFactory;

class UserMappingCreator implements MappingCreatorInterface
{
    public function create(MappingFactory $factory)
    {
        return $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ], function ($username, $password) {
            return new User($username, $password);
        }, function (User $user) {
            return [
                'username' => $user->username,
                'password' => $user->password,
            ];
        });
    }

    public function getName()
    {
        return 'Mapped\Tests\Fixtures\User';
    }
}
