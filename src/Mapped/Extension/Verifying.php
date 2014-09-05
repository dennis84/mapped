<?php

namespace Mapped\Extension;

use Mapped\Mapping;
use Mapped\Extension;

/**
 * This extension provides a simpler API to add custom constraints.
 */
class Verifying implements Extension
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
        $mapping->addConstraint(new \Mapped\Constraint\Callback($message, $func));
        return $mapping;
    }
}
