<?php

namespace Mapped\Tests\Extension;

use Mapped\Extension\Form;

class FormTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $mapping = $this->getMockBuilder('Mapped\Mapping')
            ->disableOriginalConstructor()
            ->getMock();

        $form = new Form($mapping, [
            'username' => new Form($mapping, [], ['username']),
            'password' => new Form($mapping, [], ['password']),
            'address'  => new Form($mapping, [
                'street' => new Form($mapping, [], ['address', 'street']),
                'city'   => new Form($mapping, [], ['address', 'city']),
            ], ['address']),
            'nested' => new Form($mapping, [
                'tags' => new Form($mapping, [
                    0 => new Form($mapping, [], ['nested', 'tags', 0]),
                    1 => new Form($mapping, [], ['nested', 'tags', 1]),
                ], ['nested', 'tags']),
            ], ['nested']),
        ]);

        $this->assertSame('username', $form['username']->getName());
        $this->assertSame('password', $form['password']->getName());
        $this->assertSame('address[]', $form['address']->getName());
        $this->assertSame('address[street]', $form['address']['street']->getName());
        $this->assertSame('address[city]', $form['address']['city']->getName());
        $this->assertSame('nested[tags][0]', $form['nested']['tags'][0]->getName());
        $this->assertSame('nested[tags][1]', $form['nested']['tags'][1]->getName());
        $this->assertSame('nested[tags][]', $form['nested']['tags']->getName());
    }

    public function testOffsetGetAndExists()
    {
        $mapping = $this->getMockBuilder('Mapped\Mapping')
            ->disableOriginalConstructor()
            ->getMock();

        $form = new Form($mapping, [
            'foo' => new Form($mapping),
            'bar' => new Form($mapping),
        ]);

        $this->assertNotNull($form['foo']);
        $this->assertNull($form['baz']);
        $this->assertTrue(isset($form['foo']));
        $this->assertFalse(isset($form['baz']));
    }

    public function testOffsetSet()
    {
        $this->setExpectedException('RuntimeException');
        $mapping = $this->getMockBuilder('Mapped\Mapping')
            ->disableOriginalConstructor()
            ->getMock();

        $form = new Form($mapping);
        $form['foo'] = $form;
    }

    public function testOffsetUnset()
    {
        $this->setExpectedException('RuntimeException');
        $mapping = $this->getMockBuilder('Mapped\Mapping')
            ->disableOriginalConstructor()
            ->getMock();

        $form = new Form($mapping);
        unset($form['foo']);
    }
}
