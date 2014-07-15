<?php

namespace Mapped\Tests\Integration;

use Mapped\MappingFactory;
use Mapped\Tests\Fixtures\Post;
use Mapped\Tests\Fixtures\Attribute;

class MultipleTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new MappingFactory();
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
        $factory = new MappingFactory();
        $mapping = $factory->mapping([
            'choices' => $factory->mapping()->multiple(),
        ]);

        $result = $mapping->apply(['choices' => []]);
        $this->assertSame($result, ['choices' => []]);
    }

    public function testC()
    {
        $factory = new MappingFactory();
        $mapping = $factory->mapping([
            'choices' => $factory->mapping()->nonEmptyText()->multiple(),
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $mapping->apply(['choices' => ['']]);
    }

    public function testD()
    {
        $factory = new MappingFactory();

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

        $this->assertSame('Foo', $result->getTitle());
        $this->assertSame(['foo', 'bar'], $result->getTags());
        $this->assertSame('Foo', $result->getAttributes()[0]->getName());
        $this->assertSame('Bar', $result->getAttributes()[0]->getValue());
        $this->assertSame('Blah', $result->getAttributes()[1]->getName());
        $this->assertSame('Blub', $result->getAttributes()[1]->getValue());
    }

    public function testE()
    {
        $this->setExpectedException('InvalidArgumentException');

        $factory = new MappingFactory();
        $mapping = $factory->mapping([
            'choices' => $factory->mapping()->multiple(),
        ]);

        $mapping->apply(['choices' => 'foo']);
    }

    public function testF()
    {
        $factory = new MappingFactory();

        $mapping = $factory->mapping([
            'title'      => $factory->mapping(),
            'tags'       => $factory->mapping()->multiple(),
            'attributes' => $factory->mapping([
                'name'  => $factory->mapping(),
                'value' => $factory->mapping(),
            ], null, function (Attribute $attr) {
                return [
                    'name'  => $attr->getName(),
                    'value' => $attr->getValue(),
                ];
            })->multiple(),
        ], null, function (Post $post) {
            return [
                'title'      => $post->getTitle(),
                'tags'       => $post->getTags(),
                'attributes' => $post->getAttributes(),
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
        $factory = new MappingFactory();
        $mapping = $factory->mapping([
            'emails' => $factory->mapping()->verifying('email', function ($value) {
                return (boolean) filter_var($value, FILTER_VALIDATE_EMAIL);
            })->multiple(),
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $result = $mapping->apply([
            'emails' => ['foo@bar.de', 'blah'],
        ]);
    }

    public function testH()
    {
        $factory = new MappingFactory();
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
        $factory = new MappingFactory();
        $mapping = $factory->mapping([
            'choices' => $factory->mapping()->multiple(),
        ]);

        $result = $mapping->apply(['choices' => null]);
        $this->assertSame(['choices' => []], $result);
    }
}
