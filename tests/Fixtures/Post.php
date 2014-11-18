<?php

namespace Mapped\Tests\Fixtures;

use Symfony\Component\Validator\Constraints as Assert;

class Post
{
    /**
     * @Assert\NotBlank(message="not-blank")
     */
    protected $title;
    protected $tags = [];

    /**
     * @Assert\Valid
     */
    protected $attributes = [];

    public function __construct($title, array $tags, array $attributes)
    {
        $this->title = $title;
        $this->tags = $tags;
        $this->attributes = $attributes;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
