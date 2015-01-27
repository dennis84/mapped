<?php

namespace Mapped\Tests\Fixtures;

class Book
{
    private $title;
    private $author;

    public function __construct()
    {
        $this->author = 'N/A';
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }
}
