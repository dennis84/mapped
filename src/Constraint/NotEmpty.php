<?php

namespace Mapped\Constraint;

use Mapped\Constraint;

/**
 * NotEmpty.
 */
class NotEmpty extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function check($value)
    {
        if (is_array($value)) {
            return !empty($value);
        }

        return null !== $value && '' !== $value;
    }
}
