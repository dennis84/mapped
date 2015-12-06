<?php

namespace Mapped\Transformer;

use Mapped\Transformer;

/**
 * IntTransformer.
 */
class IntTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        if (!is_numeric($data)) {
            return $data;
        }

        return intval($data);
    }
}
