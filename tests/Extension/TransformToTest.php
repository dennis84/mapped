<?php

namespace Mapped\Tests\Extension;

use Mapped\Factory;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Book;

class TransformToTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new Factory([new \Mapped\Extension\TransformTo]);

        $mapping = $factory->mapping([
            'title'  => $factory->string(),
            'author' => $factory->string(),
        ])->transformTo('Mapped\Tests\Fixtures\Book');

        $book = $mapping->apply([
            'title' => 'LOTR',
            'author' => 'J.R.R. Tolkien',
        ]);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\Book', $book);
        $data = $mapping->unapply($book);

        $this->assertEquals([
            'title' => 'LOTR',
            'author' => 'J.R.R. Tolkien',
        ], $data);
    }

    public function testB()
    {
        $factory = new Factory([new \Mapped\Extension\TransformTo]);

        $mapping = $factory->mapping([
            'title'  => $factory->string(),
            'author' => $factory->string()->optional(),
        ])->transformTo('Mapped\Tests\Fixtures\Book');

        $book = $mapping->apply([
            'title' => 'LOTR',
        ]);

        $this->assertSame('N/A', $book->getAuthor());
    }

    public function testC()
    {
        $factory = new Factory([new \Mapped\Extension\TransformTo]);

        $book = new Book;
        $mapping = $factory->mapping([
            'title'  => $factory->string(),
            'author' => $factory->string(),
        ])->transformTo($book);

        $mapping->apply([
            'title' => 'a',
            'author' => 'b',
        ]);

        $this->assertSame('a', $book->getTitle());
        $this->assertSame('b', $book->getAuthor());
    }

    public function testD()
    {
        $factory = new Factory([new \Mapped\Extension\TransformTo]);
        $user = new User('a', 'b');

        $mapping = $factory->mapping([
            'username' => $factory->string(),
            'password' => $factory->string(),
        ])->transformTo($user);

        $user = $mapping->apply([
            'username' => 'dennis',
            'password' => 'passwd',
        ]);

        $this->assertSame('dennis', $user->username);
        $this->assertSame('passwd', $user->password);

        $data = $mapping->unapply($user);

        $this->assertEquals([
            'username' => 'dennis',
            'password' => 'passwd',
        ], $data);
    }
}
