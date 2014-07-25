<?php

namespace Mapped\Tests\Fixtures;

use Symfony\Component\Validator\Constraints as Assert;

class User
{
    /**
     * @Assert\NotBlank(message="not-blank")
     */
    public $username;
    public $password;
    public $address;

    public function __construct($username, $password, $address  = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->address  = $address;
    }
}
