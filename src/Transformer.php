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
     *
     * @return mixed
     */
    public function transform($data)
    {
        return $data;
    }

    /**
     * Transforms the unapplied data.
     *
     * @param mixed $data The unapplie data
     *
     * @return mixed
     */
    public function reverseTransform($data)
    {
        return $data;
    }
}
