<?php

namespace Mapped\Tests\Fixtures;

use Symfony\Component\Validator\Constraints as Assert;

class Address
{
    /**
     * @Assert\Length(min=5)
     * @Assert\NotBlank
     */
    public $city;

    /**
     * @Assert\Length(min=5)
     * @Assert\NotBlank
     */
    public $street;

    public $location;

    public function __construct($city, $street, $location = null)
    {
        $this->city     = $city;
        $this->street   = $street;
        $this->location = $location;
    }
}
