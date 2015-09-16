<?php

namespace Mapped\Tests\Fixtures;

class User
{
    /** @var string */
    public $username;

    /** @var string */
    public $password;

    /** @var Address */
    public $address;

    public function __construct($username, $password, $address  = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->address = $address;
    }
}
