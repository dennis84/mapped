<?php

namespace Mapped\Tests\Extension;

use Mapped\Factory;
use Mapped\Tests\Fixtures\User;

class FormsTest extends \PHPUnit_Framework_TestCase
{
    public function testA()
    {
        $factory = new Factory([new \Mapped\Extension\Forms]);
        $mapping = $factory->mapping([
            'username' => $factory->mapping(),
            'password' => $factory->mapping(),
        ], function ($username, $password) {
            return new User($username, $password);
        }, function (User $user) {
            return [
                'username' => $user->username,
                'password' => $user->password,
            ];
        });

        $form = $mapping->form();
        $form->bind([
            'username' => 'dennis',
            'password' => 'passwd',
        ]);

        $this->assertTrue($form->isValid());
        $this->assertInstanceOf('Mapped\Tests\Fixtures\User', $form->getData());
        $this->assertSame('dennis', $form['username']->getData());
        $this->assertSame('passwd', $form['password']->getData());

        $form->fill($form->getData());

        $this->assertSame([
            'username' => 'dennis',
            'password' => 'passwd',
        ], $form->getValue());

        $this->assertSame('dennis', $form['username']->getValue());
        $this->assertSame('passwd', $form['password']->getValue());
    }
    
    public function testB()
    {
        $factory = new Factory([new \Mapped\Extension\Forms]);
        $mapping = $factory->mapping([
            'username' => $factory->mapping()->notEmpty(),
            'password' => $factory->mapping(),
        ], function ($username, $password) {
            return new User($username, $password);
        });

        $form = $mapping->form();
        $form->bind(['username' => '']);

        $this->assertFalse($form->isValid());
        $this->assertCount(2, $form->getErrors());
        $this->assertCount(1, $form['username']->getErrors());
        $this->assertCount(1, $form['password']->getErrors());
        $this->assertSame('error.not_empty', $form['username']->getErrors()[0]->getMessage());
        $this->assertSame('error.required', $form['password']->getErrors()[0]->getMessage());
    }
}
