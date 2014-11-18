<?php

namespace Mapped;

/**
 * Error.
 */
class Error
{
    protected $message;
    protected $propertyPath = [];

    /**
     * Constructor.
     *
     * @param string $message      The error message
     * @param array  $propertyPath The property path
     */
    public function __construct($message, array $propertyPath = [])
    {
        $this->message = $message;
        $this->propertyPath = $propertyPath;
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
