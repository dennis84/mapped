<?php

namespace Mapped\Transformer;

use Mapped\Transformer;

/**
 * BoolTransformer.
 */
class BoolTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        if ('false' === $data) {
            return false;
        }

        return (bool) $data;
    }
}
