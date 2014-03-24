<?php

namespace Mapped\Constraint;

use Mapped\Constraint;

/**
 * Boolean.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Boolean extends Constraint
{
    /**
     * {@inheritDoc}
     */
    public function check($value)
    {
        return 'true' === $value
            || 'false' === $value
            || true === $value
            || false === $value;
    }
}
