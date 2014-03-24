<?php

namespace Mapped;

/**
 * Transformer.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
abstract class Transformer
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
