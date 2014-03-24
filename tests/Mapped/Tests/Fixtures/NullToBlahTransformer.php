<?php

namespace Mapped\Tests\Fixtures;

use Mapped\Transformer;

class NullToBlahTransformer extends Transformer
{
    public function transform($data)
    {
        return null === $data ? 'blah' : $data;
    }
}
