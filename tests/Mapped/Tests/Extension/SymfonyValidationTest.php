<?php

namespace Mapped\Tests\Extension;

use Mapped\MappingFactory;
use Mapped\ValidationException;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Address;
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
}
