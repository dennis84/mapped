<?php

namespace Mapped\Tests\Fixtures;

use Symfony\Component\Validator\Constraints as Assert;

class Post
{
    /**
     * @Assert\NotBlank(message="not-blank")
     */
    public $title;
    public $tags = [];

    /**
     * @Assert\Valid
     */
    public $attributes = [];

    public function __construct($title, array $tags, array $attributes)
    {
        $this->title = $title;
        $this->tags = $tags;
        $this->attributes = $attributes;
    }
}
