<?php

namespace Mapped;

/**
 * Constraint.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
abstract class Constraint
{
    protected $message;
    protected $checked = false;

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
     * Checks if the submitted value is valid or not.
     *
     * @param mixed $value The value
     *
     * @return boolean
     */
    abstract public function check($value);

    /**
     * Validates the given data against this constraint. If the constraint was
     * already triggered before, then it will return the last result.
     *
     * @param mixed $data The data
     *
     * @return boolean
     */
    public function validate(Mapping $mapping, $data)
    {
        if (true === $this->checked) {
            return;
        }

        $this->checked = true;

        if (true === $this->check($data)) {
            return;
        }

        return new Error($mapping, $this->message);
    }
}
