<?php

namespace Mapped\Transformer;

use Mapped\Transformer;

/**
 * Boolean.
 */
class Boolean extends Transformer
{
    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        if ('false' === $data) {
            $data = false;
        }

        return (boolean) $data;
    }
}
