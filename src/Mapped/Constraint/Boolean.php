<?php

namespace Mapped\Constraint;

use Mapped\Constraint;

/**
 * Boolean.
 */
class Boolean extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function check($value)
    {
        return 'true' === $value
            || 'false' === $value
            || true === $value
            || false === $value;
    }
}
