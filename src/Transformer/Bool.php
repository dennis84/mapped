<?php

namespace Mapped\Transformer;

use Mapped\Transformer;

/**
 * Bool.
 */
class Bool extends Transformer
{
    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        if ('false' === $data) {
            $data = false;
        }

        return (bool) $data;
    }
}
