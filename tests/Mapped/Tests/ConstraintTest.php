<?php

namespace Mapped\Tests;

class ConstraintTest extends MappedTestCase
{
    public function testValidate()
    {
        $mapping = $this->createMapping();
        $constraint = new \Mapped\Constraint\Number('');
        $constraint->validate($mapping, 42);
    }

    public function testValidateFail()
    {
        $mapping = $this->createMapping();
        $constraint = new \Mapped\Constraint\Number('');
        $this->setExpectedException('Mapped\ValidationException');
        $constraint->validate($mapping, 'foo');
    }
}
