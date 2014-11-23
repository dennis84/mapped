<?php

namespace Mapped\Constraint;

use Mapped\Constraint;

/**
 * Bool.
 */
class Bool extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function check($value)
    {
        return is_bool($value);
    }
}
