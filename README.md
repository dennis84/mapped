# Mapped

Mapped is a lightweight data transformation and validation tool. You can use it
for JSON (de)serialization, form handling and many more.

[![Build Status](https://travis-ci.org/dennis84/mapped.svg?branch=master)](https://travis-ci.org/dennis84/mapped)
[![Coverage Status](https://coveralls.io/repos/dennis84/mapped/badge.png?branch=master)](https://coveralls.io/r/dennis84/mapped?branch=master)

## Quick Example

```php
<?php

$factory = new Factory;

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
    'username' => 'dennis',
    'password' => 'passwd',
]);

$data = $mapping->unapply($user);
```

## More examples

Mapped has a pretty comprehensive test coverage that demonstrates [the whole bunch of functionality](https://github.com/dennis84/mapped/tree/master/tests).
