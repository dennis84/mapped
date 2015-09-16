<?php

namespace Mapped\Tests;

use Mapped\ReflectionFactory;
use Mapped\Tests\Fixtures\User;

class ReflectionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new ReflectionFactory;

        $mapping = $factory->of('Mapped\Tests\Fixtures\Book');
        $book = $mapping->apply([
            'title' => 'LOTR',
            'author' => 'J.R.R. Tolkien',
        ]);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\Book', $book);
        $this->assertSame('LOTR', $book->getTitle());
        $this->assertSame('J.R.R. Tolkien', $book->getAuthor());
    }

    public function testB()
    {
        $factory = new ReflectionFactory;

        $mapping = $factory->of('Mapped\Tests\Fixtures\User');
        $user = $mapping->apply([
            'username' => 'dennis',
            'password' => 'passwd',
        ]);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $user);
        $this->assertSame('dennis', $user->username);
        $this->assertSame('passwd', $user->password);
        $this->assertNull($user->address);

        $this->assertSame([
            'username' => 'dennis',
            'password' => 'passwd',
            'address' => null,
        ], $mapping->unapply($user));
    }

    public function testC()
    {
        $factory = new ReflectionFactory;

        $mapping = $factory->of('Mapped\Tests\Fixtures\User');
        $user = $mapping->apply([
            'username' => 'dennis',
            'password' => 'passwd',
            'address' => [
                'street' => 'Foo',
                'city' => 'Bar',
            ],
        ]);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $user);
        $this->assertSame('dennis', $user->username);
        $this->assertSame('passwd', $user->password);
        $this->assertInstanceOf('Mapped\Tests\Fixtures\Address', $user->address);
        $this->assertSame('Foo', $user->address->street);
        $this->assertSame('Bar', $user->address->city);
        $this->assertNull($user->address->location);

        $this->assertEquals([
            'username' => 'dennis',
            'password' => 'passwd',
            'address' => [
                'street' => 'Foo',
                'city' => 'Bar',
                'location' => null,
            ],
        ], $mapping->unapply($user));
    }

    public function testD()
    {
        $this->markTestSkipped('Optional props should be optional, required props should be required');
        $this->setExpectedException('Mapped\ValidationException');

        $factory = new ReflectionFactory;

        $mapping = $factory->of('Mapped\Tests\Fixtures\User');
        $user = $mapping->apply([
            'username' => 'dennis',
        ]);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $user);
        $this->assertSame('dennis', $user->username);
    }

    public function testE()
    {
        $factory = new ReflectionFactory;

        $mapping = $factory->of('Mapped\Tests\Fixtures\Post');
        $post = $mapping->apply([
            'title' => 'Hello World',
            'tags' => ['foo', 'bar', 'baz'],
            'attributes' => [[
                'name' => 'foo',
                'value' => 'bar',
            ]],
        ]);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\Post', $post);
        $this->assertSame('Hello World', $post->title);
        $this->assertSame(['foo', 'bar', 'baz'], $post->tags);

        $this->assertEquals([
            'title' => 'Hello World',
            'tags' => ['foo', 'bar', 'baz'],
            'attributes' => [[
                'name' => 'foo',
                'value' => 'bar',
            ]],
        ], $mapping->unapply($post));
    }

    public function testF()
    {
        $factory = new ReflectionFactory;
        $this->assertSame(true, $factory->of('bool')->apply('true'));
        $this->assertSame(12, $factory->of('int')->apply('12'));
        $this->assertSame(12.2, $factory->of('float')->apply('12.2'));
        $this->assertSame([1], $factory->of('int[]')->apply(['1']));
        $this->assertSame([1], $factory->of('array')->apply([1]));
        $this->assertSame(['1'], $factory->of('array')->apply(['1']));

        $expected = new \stdClass;
        $res = $expected->foo = 'bar';
        $this->assertEquals($expected, $factory->of('stdClass')->apply(['foo' => 'bar']));
    }
}
