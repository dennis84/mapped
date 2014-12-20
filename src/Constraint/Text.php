<?php

namespace Mapped\Constraint;

use Mapped\Constraint;

/**
 * Text.
 */
class Text extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function check($value)
    {
        return is_string($value);
    }
}
