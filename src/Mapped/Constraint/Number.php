<?php

namespace Mapped\Constraint;

use Mapped\Constraint;

/**
 * Number.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Number extends Constraint
{
    /**
     * {@inheritDoc}
     */
    public function check($value)
    {
        return is_numeric($value);
    }
}
