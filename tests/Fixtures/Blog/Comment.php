<?php

namespace Mapped\Tests\Fixtures\Blog;

use Mapped\Tests\Fixtures\User\User;

class Comment
{
    /** @var string */
    private $message;
    /** @var Mapped\Tests\Fixtures\User\User */
    private $user;

    public function __construct($message, User $user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getUser()
    {
        return $this->user;
    }
}
