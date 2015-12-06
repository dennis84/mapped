<?php

namespace Mapped\Tests\Fixtures\Blog;

use Symfony\Component\Validator\Constraints as Assert;

class Post
{
    /**
     * @Assert\NotBlank(message="not-blank")
     * @var string
     */
    public $title;

    /** @var string[] */
    public $tags = [];

    /**
     * @Assert\Valid
     * @var Attribute[]
     */
    public $attributes = [];

    public function __construct($title, array $tags, array $attributes)
    {
        $this->title = $title;
        $this->tags = $tags;
        $this->attributes = $attributes;
    }
}
