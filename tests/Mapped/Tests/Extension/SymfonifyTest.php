<?php

namespace Mapped\Tests\Integration;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\User;
use Mapped\Tests\Fixtures\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

class SymfonifyTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        /* $m = new Mapped([ */
        /*     new \Mapped\Extension\Symfonify($this->createValidator()), */
        /* ]); */

        /* $form = $m->create('', [ */
        /*     $m->create('username'), */
        /*     $m->create('password'), */
        /*     $m->create('firstName'), */
        /*     $m->create('last_name'), */
        /*     $m->create('address', [ */
        /*         $m->create('city'), */
        /*         $m->create('street') */
        /*     ], function ($city, $street) { */
        /*         return new Address($city, $street); */
        /*     }), */
        /* ], function ($username, $password, $address) { */
        /*     return new User($username, $password, $address); */
        /* }); */

        /* $request = Request::create('/', 'POST', [ */
        /*     'username' => 'dennis', */
        /*     'password' => 'demo', */
        /*     'firstName' => '', */
        /*     'last_name' => '', */
        /*     'address' => [ */
        /*         'city'   => 'Foo', */
        /*         'street' => 'Foostreet 12', */
        /*     ], */
        /* ]); */

        /* $result = $form->bindFromRequest($request); */

        /* $this->assertCount(1, $form['password']->getErrors()); */
        /* $this->assertCount(1, $form['address']['city']->getErrors()); */
        /* $this->assertCount(1, $form['firstName']->getErrors()); */
        /* $this->assertCount(1, $form['last_name']->getErrors()); */
        /* $this->assertCount(1, $form->getErrors()); */
        /* $this->assertSame('passwordValid', $form->getErrors()[0]->getMapping()); */
        /* $this->assertSame('foo', $form->getErrors()[0]->getMessage()); */
    }

    private function createValidator()
    {
        $validator = Validation::createValidatorm()
            ->enableAnnotationMapping()
            ->getValidator();

        return $validator;
    }
}
