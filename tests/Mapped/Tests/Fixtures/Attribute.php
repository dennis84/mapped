<?php

namespace Mapped\Tests\Fixtures;

use Symfony\Component\Validator\Constraints as Assert;

class Attribute
{
    /**
     * @Assert\NotBlank(message="not-blank")
     */
    protected $name;
    protected $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }
}
