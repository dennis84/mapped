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
     * Checks if the value is a text.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     *
     * @return Mapping
     */
    public function text(Mapping $mapping, $message = 'error.text')
    {
        $mapping->addConstraint(new \Mapped\Constraint\Text($message));
        $mapping->transform(new \Mapped\Transformer\Text);
        return $mapping;
    }

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
        return $mapping->addConstraint(new \Mapped\Constraint\NonEmptyText($message));
    }

    /**
     * Checks if the value is numeric and converts it to an int.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     *
     * @return Mapping
     */
    public function int(Mapping $mapping, $message = 'error.int')
    {
        $mapping->addConstraint(new \Mapped\Constraint\Number($message));
        $mapping->transform(new \Mapped\Transformer\Int);
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
