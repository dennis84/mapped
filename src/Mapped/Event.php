<?php

namespace Mapped;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

/**
 * Event.
 */
class Event extends BaseEvent
{
    protected $mapping;
    protected $result;
    protected $data;
    protected $errors = [];
    protected $propertyPath = [];

    /**
     * Constructor.
     *
     * @param Mapping $mapping      The mapping object
     * @param mixed   $data         The applied or unapplied data
     * @param mixed   $result       The mapping result
     * @param Error[] $errors       An array of errors
     * @param array   $propertyPath The property path
     */
    public function __construct(
        Mapping $mapping,
        $data = null,
        $result = null,
        array $errors = [],
        array $propertyPath = []
    ) {
        $this->mapping = $mapping;
        $this->data = $data;
        $this->result = $result;
        $this->errors = $errors;
        $this->propertyPath = $propertyPath;
    }

    /**
     * Gets the mapping object.
     *
     * @return Mapping
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * Gets the data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the data.
     *
     * @param mixed $data The data
     */
    public function setData($data)
    {
        $this->data = $data;
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
