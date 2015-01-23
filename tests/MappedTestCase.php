<?php

namespace Mapped\Tests;

use Mapped\Mapping;
use Mapped\Emitter;

class MappedTestCase extends \PHPUnit_Framework_TestCase
{
    protected function createMapping(array $extensions = [])
    {
        return new Mapping(new Emitter, $extensions);
    }
}
