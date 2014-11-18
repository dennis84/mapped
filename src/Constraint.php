<?php

namespace Mapped;

/**
 * Constraint.
 */
abstract class Constraint
{
    protected $message;

    /**
     * Constructor.
     *
     * @param string $message The error message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Checks if the given value is valid or not.
     *
     * @param mixed $value The value
     *
     * @return boolean
     */
    abstract public function check($value);

    /**
     * Gets the error message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
