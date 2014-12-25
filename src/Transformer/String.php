<?php

namespace Mapped\Transformer;

use Mapped\Transformer;

/**
 * String.
 */
class String extends Transformer
{
    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        if (!is_string($data)) {
            return $data;
        }

        return (string) $data;
    }
}
