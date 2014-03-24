<?php

namespace Mapped\Tests\Fixtures;

use Mapped\Transformer;

class NonsenseTransformer extends Transformer
{
    public function transform($data)
    {
        return ['foo' => $data['foo'] * 10];
    }
}
