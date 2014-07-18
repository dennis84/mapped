<?php

namespace Mapped;

/**
 * ValidationException.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class ValidationException extends \Exception
{
    protected $errors = [];

    /**
     * Constructor.
     *
     * @param Error[] $errors An array of array objects
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('Validation failed');
    }

    /**
     * Returns the errors.
     *
     * return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
