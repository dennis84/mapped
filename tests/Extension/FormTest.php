<?php

namespace Mapped\Tests\Extension;

use Mapped\Extension\Form;

class FormTest extends \PHPUnit_Framework_TestCase
{
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
