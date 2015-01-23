<?php

namespace Mapped;

/**
 * Data.
 */
class Data
{
    protected $input;
    protected $result;
    protected $errors = [];
    protected $propertyPath = [];

    /**
     * Constructor.
     *
     * @param mixed   $input        The applied or unapplied data
     * @param mixed   $result       The mapping result
     * @param Error[] $errors       An array of errors
     * @param array   $propertyPath The property path
     */
    public function __construct($input, $result = null, array $errors = [], array $propertyPath = [])
    {
        $this->input = $input;
        $this->result = $result;
        $this->errors = $errors;
        $this->propertyPath = $propertyPath;
    }

    /**
     * Gets the input.
     *
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Sets the input.
     *
     * @param mixed $input The input
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * Sets the result.
     *
     * @param mixed $result The result data
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * Gets the result.
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Adds an error.
     *
     * @param Error $error
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }

    /**
     * Removes an error.
     *
     * @param Error $error
     */
    public function removeError(Error $error)
    {
        $index = array_search($error, $this->errors);
        if (false !== $index) {
            unset($this->errors[$index]);
        }
    }

    /**
     * Sets the errors.
     *
     * @param Error[] An array of error objects
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Gets the errors.
     *
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Gets the property path.
     *
     * @return array
     */
    public function getPropertyPath()
    {
        return $this->propertyPath;
    }
}
