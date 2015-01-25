<?php

namespace Mapped;

/**
 * Transformer.
 */
class Transformer
{
    /**
     * Transforms the applied data.
     *
     * @param mixed $data The applied data
     */
    public function transform($data)
    {
        return $data;
    }

    /**
     * Transforms the unapplied data.
     *
     * @param mixed $data The unapplie data
     */
    public function reverseTransform($data)
    {
        return $data;
    }
}
