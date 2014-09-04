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
        return is_bool($value);
    }
}
