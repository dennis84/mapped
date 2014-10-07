<?php

namespace Mapped\Tests\Fixtures;

use Mapped\Mapping;
use Mapped\ExtensionInterface;

class FooExtension implements ExtensionInterface
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
