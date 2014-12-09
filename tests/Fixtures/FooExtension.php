<?php

namespace Mapped\Tests\Fixtures;

use Mapped\Mapping;
use Mapped\ExtensionInterface;

class FooExtension implements ExtensionInterface
{
    public function foo(Mapping $mapping)
    {
        return $mapping;
    }
}
