<?php

namespace Mapped\Tests\Extension;

use Mapped\Factory;
use Mapped\ValidationException;
use Mapped\Tests\Fixtures\Blog\Post;
use Mapped\Tests\Fixtures\Blog\Attribute;

class MultipleTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'choices' => $factory->mapping()->multiple(),
        ]);

        $result = $mapping->apply([
            'choices' => ['foo', 'bar', 'baz'],
        ]);

        $this->assertSame($result, [
            'choices' => ['foo', 'bar', 'baz'],
        ]);
    }

    public function testB()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'choices' => $factory->mapping()->multiple(),
        ]);

        $result = $mapping->apply(['choices' => []]);
        $this->assertSame($result, ['choices' => []]);
    }

    public function testC()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'choices' => $factory->mapping()->notEmpty()->multiple(),
        ]);

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply(['choices' => ['']]);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame(['choices', 0], $errors[0]->getPropertyPath());
            $this->assertSame('error.not_empty', $errors[0]->getMessage());
            throw $e;
        }
    }

    public function testD()
    {
        $factory = new Factory;

        $mapping = $factory->mapping([
            'title'      => $factory->mapping(),
            'tags'       => $factory->mapping()->multiple(),
            'attributes' => $factory->mapping([
                'name'  => $factory->mapping(),
                'value' => $factory->mapping(),
            ], function ($name, $value) {
                return new Attribute($name, $value);
            })->multiple(),
        ], function ($title, array $tags, array $attrs) {
            return new Post($title, $tags, $attrs);
        });

        $result = $mapping->apply([
            'title' => 'Foo',
            'tags' => ['foo', 'bar'],
            'attributes' => [
                ['name' => 'Foo', 'value' => 'Bar'],
                ['name' => 'Blah', 'value' => 'Blub'],
            ],
        ]);

        $this->assertSame('Foo', $result->title);
        $this->assertSame(['foo', 'bar'], $result->tags);
        $this->assertSame('Foo', $result->attributes[0]->name);
        $this->assertSame('Bar', $result->attributes[0]->value);
        $this->assertSame('Blah', $result->attributes[1]->name);
        $this->assertSame('Blub', $result->attributes[1]->value);
    }

    public function testE()
    {
        $this->setExpectedException('InvalidArgumentException');

        $factory = new Factory;
        $mapping = $factory->mapping([
            'choices' => $factory->mapping()->multiple(),
        ]);

        $mapping->apply(['choices' => 'foo']);
    }

    public function testF()
    {
        $factory = new Factory;

        $mapping = $factory->mapping([
            'title'      => $factory->mapping(),
            'tags'       => $factory->mapping()->multiple(),
            'attributes' => $factory->mapping([
                'name'  => $factory->mapping(),
                'value' => $factory->mapping(),
            ], null, function (Attribute $attr) {
                return [
                    'name'  => $attr->name,
                    'value' => $attr->value,
                ];
            })->multiple(),
        ], null, function (Post $post) {
            return [
                'title'      => $post->title,
                'tags'       => $post->tags,
                'attributes' => $post->attributes,
            ];
        });

        $post = new Post('Foo', ['foo', 'bar', 'baz'], [
            new Attribute('bla', 'blubb'),
            new Attribute('hello', 'world'),
        ]);

        $result = $mapping->unapply($post);

        $this->assertSame('foo', $result['tags']['0']);
        $this->assertSame('bar', $result['tags']['1']);
        $this->assertSame('bla', $result['attributes']['0']['name']);
        $this->assertSame('blubb', $result['attributes']['0']['value']);
        $this->assertSame('hello', $result['attributes']['1']['name']);
        $this->assertSame('world', $result['attributes']['1']['value']);
    }

    public function testG()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'emails' => $factory->mapping()->verifying('email', function ($value) {
                return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
            })->multiple(),
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $result = $mapping->apply([
            'emails' => ['foo@bar.de', 'blah'],
        ]);
    }

    public function testH()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'choices' => $factory->mapping()->multiple(),
        ]);

        $result = $mapping->unapply([
            'choices' => ['foo', 'bar', 'baz'],
        ]);

        $this->assertSame('foo', $result['choices']['0']);
        $this->assertSame('bar', $result['choices']['1']);
        $this->assertSame('baz', $result['choices']['2']);

        $result = $mapping->apply(['choices' => ['foo', 'bar']]);

        $this->assertSame($result, [
            'choices' => ['foo', 'bar'],
        ]);
    }

    public function testI()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'choices' => $factory->mapping()->multiple(),
        ]);

        $result = $mapping->apply(['choices' => null]);
        $this->assertSame(['choices' => []], $result);
    }

    public function testJ()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'foos' => $factory->mapping([
                'bars' => $factory->mapping()->multiple(),
            ])->multiple(),
        ]);

        $data = ['foos' => [['bars' => ['a', 'b']], ['bars' => ['c', 'd']]]];

        $result = $mapping->apply($data);
        $this->assertSame($data, $result);
    }
}
