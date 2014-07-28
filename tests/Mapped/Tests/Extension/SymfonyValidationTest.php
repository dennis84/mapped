<?php

namespace Mapped\Tests\Extension;

use Mapped\MappingFactory;
use Mapped\ValidationException;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Post;
use Mapped\Tests\Fixtures\Attribute;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class SymfonyValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testAssert()
    {
        $validator = Validation::createValidator();
        $factory = new MappingFactory([
            new \Mapped\Extension\SymfonyValidation($validator),
        ]);

        $mapping = $factory->mapping([
            'username' => $factory->mapping()->assert(
                new Assert\NotBlank(['message' => 'not-blank'])),
        ]);

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $result = $mapping->apply(['username' => '']);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('not-blank', $errors[0]->getMessage());
            $this->assertSame(['username'], $errors[0]->getPropertyPath());
            throw $e;
        }
    }

    public function testAssertObject()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $factory = new MappingFactory([
            new \Mapped\Extension\SymfonyValidation($validator),
        ]);

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
        })->enableObjectValidation();

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply([
                'title' => '',
                'tags' => [],
                'attributes' => [
                    ['name' => '', 'value' => ''],
                ],
            ]);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame('not-blank', $errors[0]->getMessage());
            $this->assertSame(['title'], $errors[0]->getPropertyPath());
            $this->assertSame('not-blank', $errors[1]->getMessage());
            $this->assertSame(['attributes', '0', 'name'], $errors[1]->getPropertyPath());
            throw $e;
        }
    }
}
