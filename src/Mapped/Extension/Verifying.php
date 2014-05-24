<?php

namespace Mapped\Extension;

use Mapped\Mapping;
use Mapped\Extension;

/**
 * This extension offers a simpler API to add custom constraints.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Verifying extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function initialize(Mapping $mapping)
    {
    }

    /**
     * Adds a constraint to the mapping object.
     *
     * @param Mapping  $mapping The mapping object
     * @param string   $message The error message
     * @param callable $check   The check method
     *
     * @return Mapping
     */
    public function verifying(Mapping $mapping, $message, callable $func)
    {
        $mapping->addConstraint(new \Mapped\Constraint\Callback($message, $func));
        return $mapping;
    }
}
