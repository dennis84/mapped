<?php

namespace Mapped\Extension;

use Mapped\Mapping;
use Mapped\ValidationException;

/**
 * Form.
 */
class Form implements \ArrayAccess
{
    private $mapping;
    private $children = [];
    private $propertyPath = [];
    private $value;
    private $data;
    private $errors = [];

    /**
     * Constructor.
     *
     * @param Mapping $mapping      The mapping object
     * @param Form[]  $children     An array of form objects
     * @param array   $propertyPath The property path
     */
    public function __construct(Mapping $mapping, array $children = [], array $propertyPath = [])
    {
        $this->mapping = $mapping;
        $this->children = $children;
        $this->propertyPath = $propertyPath;
    }

    /**
     * Binds the given data to the form.
     *
     * @param mixed $data The submitted data
     */
    public function bind($data)
    {
        try {
            $this->data = $this->mapping->apply($data);
        } catch (ValidationException $e) {
            $this->errors = $e->getErrors();
        }
    }

    /**
     * Fills the form.
     *
     * @param mixed $data The data
     */
    public function fill($data)
    {
        $this->value = $this->mapping->unapply($data);
    }

    /**
     * Returns true if this form is valid, otherwise false.
     *
     * @return bool
     */
    public function isValid()
    {
        return 0 === count($this->errors);
    }

    /**
     * Sets data.
     *
     * @param mixed $data Some data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Gets the data (the applied result).
     *
     * return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets value.
     *
     * @param mixed $value Some value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Gets the value (The unapplied result).
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets errors.
     *
     * @param Error[] $errors An array of error objects
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
     * Returns the field name (e.g. `address[street]`).
     *
     * @return string
     */
    public function getName()
    {
        $elems = $this->propertyPath;
        $name = array_shift($elems);

        if (null === $name) {
            return '';
        }

        foreach ($elems as $elem) {
            $name .= '['.$elem.']';
        }

        if (count($this->children) > 0) {
            $name .= '[]';
        }

        return $name;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->children[$offset];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->children[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Not implemented');
    }
}
