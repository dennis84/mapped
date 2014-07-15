<?php

namespace Mapped\Tests;

use Mapped\MappingFactory;
use Mapped\ValidationException;
use Mapped\Tests\Fixtures\User;

class ValidationTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new MappingFactory();
        $mapping = $factory->mapping([
            'username' => $factory->mapping()->nonEmptyText(),
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
            $this->assertSame('error.non_empty_text', $errors[0]->getMessage());
            $this->assertSame('foo', $errors[1]->getMessage());
            $this->assertSame('error.required', $errors[2]->getMessage());
            throw $e;
        }
    }
}
