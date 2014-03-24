<?php

namespace Mapped\Tests\Integration;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\Post;
use Mapped\Tests\Fixtures\Attribute;

class MultipleTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('choices')->multiple(),
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
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('choices')->multiple(),
        ]);

        $result = $mapping->apply(['choices' => []]);
        $this->assertSame($result, ['choices' => []]);
    }

    public function testC()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('choices')->multiple(),
        ]);

        $result = $mapping->apply([]);
        $this->assertSame($result, ['choices' => []]);
    }

    public function testD()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('choices')->nonEmptyText()->multiple(),
        ]);

        $result = $mapping->apply([
            'choices' => ['foo', 'bar', 'baz'],
        ]);

        $this->assertSame([
            'choices' => ['foo', 'bar', 'baz']
        ], $result);
    }

    public function testE()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('choices')->nonEmptyText()->multiple(),
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $mapping->apply(['choices' => ['']]);
    }

    public function testF()
    {
        $m = new Mapped();

        $mapping = $m->create('', [
            $m->create('title'),
            $m->create('tags')->multiple(),
            $m->create('attributes', [
                $m->create('name'),
                $m->create('value'),
            ], function ($name, $value) {
                return new Attribute($name, $value);
            })->multiple(),
        ], function ($title, array $tags, array $attrs) {
            return new Post($title, $tags, $attrs);
        });

        $result = $mapping->apply([]);

        $this->assertSame(null, $result->getTitle());
        $this->assertSame([], $result->getTags());
        $this->assertSame([], $result->getAttributes());
    }

    public function testG()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('choices', [
                $m->create('key'),
                $m->create('value'),
            ])->multiple(),
        ]);

        $data = [
            'choices' => [
                ['key' => 'foo', 'value' => 'bar'],
                ['key' => 'bla', 'value' => 'blubb'],
            ],
        ];

        $result = $mapping->apply($data);
        $this->assertSame($result, $data);
    }

    public function testH()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('choices', [
                $m->create('name'),
                $m->create('value'),
            ], function ($name, $value) {
                return new Attribute($name, $value);
            })->multiple(),
        ]);

        $data = [
            'choices' => [
                ['name' => 'foo', 'value' => 'bar'],
                ['name' => 'bla', 'value' => 'blubb'],
            ],
        ];

        $result = $mapping->apply($data);

        $this->assertInstanceOf('Mapped\Tests\Fixtures\Attribute', $result['choices'][0]);
        $this->assertInstanceOf('Mapped\Tests\Fixtures\Attribute', $result['choices'][1]);
    }

    public function testI()
    {
        $this->setExpectedException('InvalidArgumentException');

        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('choices')->multiple(),
        ]);

        $mapping->apply(['choices' => 'foo']);
    }

    public function testJ()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('choices')->multiple(),
        ]);

        $result = $mapping->unapply([
            'choices' => ['foo', 'bar', 'baz'],
        ]);

        $this->assertSame('foo', $result['choices']['0']);
        $this->assertSame('bar', $result['choices']['1']);
        $this->assertSame('baz', $result['choices']['2']);
    }

    public function testK()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('choices', [
                $m->create('key'),
                $m->create('value'),
            ])->multiple(),
        ]);

        $result = $mapping->unapply([
            'choices' => [
                ['key' => 'foo', 'value' => 'bar'],
                ['key' => 'bla', 'value' => 'blubb'],
            ],
        ]);

        $this->assertSame('foo', $result['choices']['0']['key']);
        $this->assertSame('bar', $result['choices']['0']['value']);
        $this->assertSame('bla', $result['choices']['1']['key']);
        $this->assertSame('blubb', $result['choices']['1']['value']);
    }

    public function testL()
    {
        $m = new Mapped();

        $mapping = $m->create('', [
            $m->create('title'),
            $m->create('tags')->multiple(),
            $m->create('attributes', [
                $m->create('name'),
                $m->create('value'),
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

    public function testM()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('emails')->verifying('email', function ($value) {
                return (boolean) filter_var($value, FILTER_VALIDATE_EMAIL);
            })->multiple(),
        ]);

        $result = $mapping->apply([
            'emails' => ['foo@bar.de', 'blah@blub.de'],
        ]);

        $this->assertSame([
            'emails' => ['foo@bar.de', 'blah@blub.de'],
        ], $result);
    }

    public function testO()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('emails')->verifying('email', function ($value) {
                return (boolean) filter_var($value, FILTER_VALIDATE_EMAIL);
            })->multiple(),
        ]);

        $this->setExpectedException('Mapped\ValidationException');
        $result = $mapping->apply([
            'emails' => ['foo@bar.de', 'blah'],
        ]);
    }

    public function testP()
    {
        $m = new Mapped();
        $mapping = $m->create('', [
            $m->create('choices')->multiple(),
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
}
