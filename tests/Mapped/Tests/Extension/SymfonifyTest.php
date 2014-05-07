<?php

namespace Mapped\Tests\Integration;

use Mapped\MappingFactory;
use Mapped\ValidationException;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

class SymfonifyTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $this->setExpectedException('Mapped\ValidationException');

        $factory = new MappingFactory([
            new \Mapped\Extension\Symfonify($this->createValidator()),
        ]);

        $mapping = $factory->mapping([
            'username'  => $factory->mapping(),
            'password'  => $factory->mapping(),
            'firstName' => $factory->mapping(),
            'last_name' => $factory->mapping(),
            'address'   => $factory->mapping([
                'city'   => $factory->mapping(),
                'street' => $factory->mapping()
            ], function ($city, $street) {
                return new Address($city, $street);
            }),
        ], function ($username, $password, $address) {
            return new User($username, $password, $address);
        });

        try {
            $result = $mapping->apply([
                'username'  => 'dennis',
                'password'  => 'demo',
                'firstName' => '',
                'last_name' => '',
                'address'   => [
                    'city'   => 'Foo',
                    'street' => 'Foostreet 12',
                ],
            ]);
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    private function createValidator()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        return $validator;
    }
}
