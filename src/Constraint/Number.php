<?php

namespace Mapped\Constraint;

use Mapped\Constraint;

/**
 * Number.
 */
class Number extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function check($value)
    {
        return is_numeric($value);
    }
}
