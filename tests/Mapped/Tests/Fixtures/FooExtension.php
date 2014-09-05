<?php

namespace Mapped\Tests\Fixtures;

use Mapped\Mapping;
use Mapped\Extension;

class FooExtension implements Extension
{
    public function initialize(Mapping $mapping)
    {
    }

    public function foo(Mapping $mapping)
    {
        return $mapping;
    }

    public function bar(Mapping $mapping, array $arr)
    {
        return $mapping;
    }

    public function baz()
    {
    }
}
