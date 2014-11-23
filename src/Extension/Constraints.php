<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Constraint;
use Mapped\Mapping;

/**
 * Enriches mapping objects with validation and transformation methods.
 */
class Constraints implements ExtensionInterface
{
    /**
     * Checks if the value is not empty.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     *
     * @return Mapping
     */
    public function nonEmptyText(Mapping $mapping, $message = 'error.non_empty_text')
    {
        $mapping->addConstraint(new \Mapped\Constraint\NonEmptyText($message));
        return $mapping;
    }

    /**
     * Checks if the value is numeric and converts it to an integer.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     *
     * @return Mapping
     */
    public function integer(Mapping $mapping, $message = 'error.integer')
    {
        $mapping->addConstraint(new \Mapped\Constraint\Number($message));
        $mapping->transform(new \Mapped\Transformer\Integer);
        return $mapping;
    }

    /**
     * Checks if the value is numeric and converts it to a float.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     *
     * @return Mapping
     */
    public function float(Mapping $mapping, $message = 'error.float')
    {
        $mapping->addConstraint(new \Mapped\Constraint\Number($message));
        $mapping->transform(new \Mapped\Transformer\Float);
        return $mapping;
    }

    /**
     * Checks if the value is boolean.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     *
     * @return Mapping
     */
    public function bool(Mapping $mapping, $message = 'error.bool')
    {
        $mapping->transform(new \Mapped\Transformer\Bool);
        $mapping->addConstraint(new \Mapped\Constraint\Bool($message));
        return $mapping;
    }
}
