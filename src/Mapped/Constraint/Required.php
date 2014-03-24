<?php

namespace Mapped\Constraint;

use Mapped\Constraint;

/**
 * Required.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Required extends Constraint
{
    /**
     * {@inheritDoc}
     */
    public function check($value)
    {
        return null !== $value && '' !== $value;
    }
}
