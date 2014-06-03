# Mapped

A lightweight serialization library for PHP.

[![Build Status](https://travis-ci.org/dennis84/mapped.svg?branch=master)](https://travis-ci.org/dennis84/mapped)
[![Coverage Status](https://coveralls.io/repos/dennis84/mapped/badge.png?branch=master)](https://coveralls.io/r/dennis84/mapped?branch=master)

## Quick Example

```php
<?php

$factory = new MappingFactory();

$mapping = $factory->mapping([
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

$user = $mapping->apply([
    'username' => 'dennis84',
    'password' => 'password',
]);

$data = $mapping->unapply($user);
```

## Classes

`add/apply/unapply` mappings by name with the `MappingCollection`:

```php
$mappings = new MappingCollection();
$mappings->add(User::class, $userMapping);

$user = $mappings->apply([...], User::class);
$data = $mappings->unapply($user, User::class);
```

Define mappings via creator classes:

```php
class UserMappingCreator implements MappingCreatorInterface
{
    public function create(MappingFactory $factory)
    {
        return $factory->mapping(...);
    }

    public function getName()
    {
        return User::class;
    }
}

$userMapping = new UserMappingCreator();
$factory  = new MappingCollectionFactory();

// @var MappingCollection
$mappings = $factory->create([$usermapping]);
```
