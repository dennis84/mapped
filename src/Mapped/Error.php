<?php

namespace Mapped;

/**
 * Error.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Error
{
    protected $mapping;
    protected $message;

    /**
     * Constructor.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     */
    public function __construct(Mapping $mapping, $message)
    {
        $this->mapping = $mapping;
        $this->message = $message;
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
}
