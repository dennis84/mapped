<?php

namespace Mapped;

/**
 * Error.
 */
class Error
{
    protected $mapping;
    protected $message;
    protected $propertyPath = [];

    /**
     * Constructor.
     *
     * @param Mapping $mapping      The mapping object
     * @param string  $message      The error message
     * @param array   $propertyPath The property path
     */
    public function __construct(Mapping $mapping, $message, array $propertyPath = [])
    {
        $this->mapping = $mapping;
        $this->message = $message;
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
     * Gets the message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
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
