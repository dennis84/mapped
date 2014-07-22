<?php

namespace Mapped\Transformer;

use Mapped\Transformer;

/**
 * Float.
 */
class Float extends Transformer
{
    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        if (!is_numeric($data)) {
            return $data;
        }

        return floatval($data);
    }
}
