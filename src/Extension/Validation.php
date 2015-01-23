<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Mapping;

/**
 * Validation extension.
 */
class Validation implements ExtensionInterface
{
    /**
     * Adds a callback constraint to the mapping.
     *
     * @param Mapping  $mapping The mapping object
     * @param string   $message The error message
     * @param callable $check   The check function
     *
     * @return Mapping
     */
    public function verifying(Mapping $mapping, $message, callable $func)
    {
        return $mapping->validate(new \Mapped\Constraint\Callback($message, $func));
    }

    /**
     * Checks if the value is not empty.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     *
     * @return Mapping
     */
    public function notEmpty(Mapping $mapping, $message = 'error.not_empty')
    {
        return $mapping->validate(new \Mapped\Constraint\NotEmpty($message));
    }
}
