<?php

namespace Mapped\Constraint;

use Mapped\Constraint;

/**
 * Callback.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Callback extends Constraint
{
    protected $message;
    protected $callback;

    /**
     * Constructor.
     *
     * @param string   $message  The error message
     * @param callable $callback The callback function
     */
    public function __construct($message, callable $callback)
    {
        $this->message = $message;
        $this->callback = $callback;
    }

    /**
     * {@inheritDoc}
     */
    public function check($value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        return call_user_func_array($this->callback, $value);
    }
}
