<?php

namespace Mapped\Transformer;

use Mapped\Transformer;

/**
 * Text.
 */
class Text extends Transformer
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
