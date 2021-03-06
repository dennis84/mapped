<?php

namespace Mapped\Constraint;

use Mapped\Constraint;

/**
 * Type.
 */
class Type extends Constraint
{
    private $type;

    /**
     * Constructor.
     *
     * @param string $message The error message
     * @param string $type    The type
     */
    public function __construct($message, $type)
    {
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function check($value)
    {
        if (null === $value) {
            return;
        }

        $fn = 'is_' . $this->type;

        if (function_exists($fn)) {
            return $fn($value);
        }

        return false;
    }
}
