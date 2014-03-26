<?php

namespace Mapped\Tests;

use Mapped\Mapped;
use Mapped\Tests\Fixtures\NonsenseTransformer;

class TransformationTest extends \PHPUnit_Framework_TestCase
{
    public function test_transform_and_apply_order()
    {
        $test = $this;
        $m = new Mapped();

        $mapping = $m->mapping([
            'foo' => $m->mapping()->integer(),
        ], function ($foo) use ($test) {
            // The apply must come after transformation.
            $test->assertSame(420, $foo);
        });

        $mapping->transform(new NonsenseTransformer());
        $mapping->apply(['foo' => '42.2']);
    }
}
