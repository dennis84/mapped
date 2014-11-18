<?php

namespace Mapped\Tests\Fixtures;

use Symfony\Component\Validator\Constraints as Assert;

class Address
{
    /**
     * @Assert\NotBlank(message="not-blank")
     */
    public $city;
    public $street;
    public $location;

    public function __construct($city, $street, $location = null)
    {
        $this->city     = $city;
        $this->street   = $street;
        $this->location = $location;
    }
}
