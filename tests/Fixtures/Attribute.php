<?php

namespace Mapped\Tests\Fixtures;

use Symfony\Component\Validator\Constraints as Assert;

class Attribute
{
    /**
     * @Assert\NotBlank(message="not-blank")
     * @var string
     */
    public $name;

    /** @var string */
    public $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}
