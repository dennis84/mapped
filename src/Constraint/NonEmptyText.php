<?php

namespace Mapped\Constraint;

use Mapped\Constraint;

/**
 * NonEmptyText.
 */
class NonEmptyText extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function check($value)
    {
        return is_string($value) && null !== $value && '' !== $value;
    }
}
