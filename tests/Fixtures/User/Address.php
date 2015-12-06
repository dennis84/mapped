<?php

namespace Mapped\Tests\Fixtures\User;

use Symfony\Component\Validator\Constraints as Assert;

class Address
{
    /**
     * @var string
     * @Assert\NotBlank(message="not-blank")
     */
    public $city;

    /** @var string */
    public $street;

    /** @var Location */
    public $location;

    public function __construct($city, $street, $location = null)
    {
        $this->city = $city;
        $this->street = $street;
        $this->location = $location;
    }
}
