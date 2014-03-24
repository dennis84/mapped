<?php

namespace Mapped\Tests;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Mapped\Mapping;

class MappedTestCase extends \PHPUnit_Framework_TestCase
{
    protected function createMapping($name, array $extensions = [])
    {
        return new Mapping($name, new EventDispatcher(), $extensions);
    }
}
