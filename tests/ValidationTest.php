<?php

namespace Mapped\Tests;

use Mapped\Factory;
use Mapped\ValidationException;
use Mapped\Tests\Fixtures\User\User;
use Mapped\Tests\Fixtures\User\Address;

class ValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping()->notEmpty(),
            'password' => $factory->mapping()->verifying('error.min_length', function ($value) {
                return strlen($value) > 5;
            }),
            'address' => $factory->mapping([
                'city' => $factory->mapping()->notEmpty(),
                'street' => $factory->mapping()->notEmpty(),
                'location' => $factory->mapping([
                    'lat' => $factory->mapping()->notEmpty(),
                    'lng' => $factory->mapping()->notEmpty(),
                ]),
            ]),
        ]);

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $result = $mapping->apply([
                'username' => '',
                'password' => 'pass',
                'address' => [
                    'city' => '',
                    'street' => '',
                ],
            ]);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertCount(5, $errors);
            $this->assertSame(['username'], $errors[0]->getPropertyPath());
            $this->assertSame('error.not_empty', $errors[0]->getMessage());
            $this->assertSame(['password'], $errors[1]->getPropertyPath());
            $this->assertSame('error.min_length', $errors[1]->getMessage());
            $this->assertSame(['address', 'city'], $errors[2]->getPropertyPath());
            $this->assertSame('error.not_empty', $errors[2]->getMessage());
            $this->assertSame(['address', 'street'], $errors[3]->getPropertyPath());
            $this->assertSame('error.not_empty', $errors[3]->getMessage());
            $this->assertSame(['address', 'location'], $errors[4]->getPropertyPath());
            $this->assertSame('error.required', $errors[4]->getMessage());
            throw $e;
        }
    }

    public function testB()
    {
        $factory = new Factory;
        $mapping = $factory->mapping()
            ->verifying('a', function ($value) {
                $this->assertSame('foo', $value);
                return false;
            })
            ->verifying('b', function ($value) {
                $this->fail();
            });

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply('foo');
        } catch (ValidationException $e) {
            $this->assertSame('a', $e->getErrors()[0]->getMessage());
            throw $e;
        }
    }

    public function testC()
    {
        $factory = new Factory;
        $mapping = $factory->mapping()
            ->verifying('a', function ($value) {
                $this->assertSame('foo', $value);
                return true;
            })
            ->verifying('b', function ($value) {
                $this->assertSame('foo', $value);
                return false;
            });

        $this->setExpectedException('Mapped\ValidationException');

        try {
            $mapping->apply('foo');
        } catch (ValidationException $e) {
            $this->assertSame('b', $e->getErrors()[0]->getMessage());
            throw $e;
        }
    }

    public function testD()
    {
        $factory = new Factory;
        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ], function ($username, $password) {
            return new User($username, $password);
        })->verifying('foo', function ($user) {
            $this->assertInstanceOf('Mapped\Tests\Fixtures\User\User', $user);
            return true;
        });

        $mapping->apply([
            'username' => 'dennis',
            'password' => 'password',
        ]);
    }
}
