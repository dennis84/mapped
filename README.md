# Mapped

A lightweight serialization library for PHP.

[![Build Status](https://travis-ci.org/dennis84/mapped.svg?branch=master)](https://travis-ci.org/dennis84/mapped)

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
