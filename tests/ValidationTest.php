<?php

namespace Mapped\Tests;

use Mapped\MappingFactory;
use Mapped\ValidationException;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Address;

class ValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new MappingFactory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping()->notEmpty(),
            'password' => $factory->mapping()->verifying('foo', function ($value) {
                return strlen($value) > 5;
            }),
            'accept' => $factory->mapping(),
        ]);

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $result = $mapping->apply([
                'username' => '',
                'password' => 'pass',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertCount(3, $errors);
            $this->assertSame(['username'], $errors[0]->getPropertyPath());
            $this->assertSame('error.not_empty', $errors[0]->getMessage());
            $this->assertSame(['password'], $errors[1]->getPropertyPath());
            $this->assertSame('foo', $errors[1]->getMessage());

            $this->assertSame(['accept'], $errors[2]->getPropertyPath());
            $this->assertSame('error.required', $errors[2]->getMessage());
            throw $e;
        }
    }

    public function testB()
    {
        $factory = new MappingFactory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping()->notEmpty(),
            'password' => $factory->mapping()->verifying('error.password', function ($value) {
                return false;
            }),
            'address' => $factory->mapping([
                'city' => $factory->mapping()->verifying('error.city', function ($value) {
                    return false;
                }),
                'street' => $factory->mapping(),
            ], function ($city, $street = null) {
                return new Address($city, $street);
            }),
            'accept' => $factory->mapping(),
        ], function ($username, $password, Address $address) {
            return new User($username, $password, $address);
        })->verifying('', function ($username, $password, $address, $accept) {
            $this->fail();
        });

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $result = $mapping->apply([
                'username' => '',
                'password' => 'pass',
                'address' => [
                    'city' => 'Foobar',
                ],
            ]);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertSame(['username'], $errors[0]->getPropertyPath());
            $this->assertSame('error.not_empty', $errors[0]->getMessage());

            $this->assertSame(['password'], $errors[1]->getPropertyPath());
            $this->assertSame('error.password', $errors[1]->getMessage());

            $this->assertSame(['address', 'city'], $errors[2]->getPropertyPath());
            $this->assertSame('error.city', $errors[2]->getMessage());

            $this->assertSame(['address', 'street'], $errors[3]->getPropertyPath());
            $this->assertSame('error.required', $errors[3]->getMessage());

            $this->assertSame(['accept'], $errors[4]->getPropertyPath());
            $this->assertSame('error.required', $errors[4]->getMessage());
            throw $e;
        }
    }
}
