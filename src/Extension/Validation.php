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
}
