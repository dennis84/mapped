<?php

namespace Mapped\Extension;

use Mapped\Extension;
use Mapped\Constraint;
use Mapped\Mapping;
use Mapped\Events;
use Mapped\Event;

/**
 * The default constraints extension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Constraints extends Extension
{
    /**
     * Check if this mapping is empty or not.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     *
     * @return Mapping
     */
    public function required(Mapping $mapping, $message = 'error.required')
    {
        $mapping->addConstraint(new \Mapped\Constraint\Required($message));
        $disp = $mapping->getDispatcher();

        $disp->addListener(
            Events::BEFORE_TRANSFORM,
            function (Event $event) use ($mapping) {
                $mapping->validate($event->getData());
            }
        );

        return $mapping;
    }

    /**
     * Checks if the mapping value is not empty.
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
     * Checks if the mapping value is numeric and convert it to an integer.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     *
     * @return Mapping
     */
    public function integer(Mapping $mapping, $message = 'error.integer')
    {
        $mapping->addConstraint(new \Mapped\Constraint\Number($message));
        $mapping->transform(new \Mapped\Transformer\Integer());
        return $mapping;
    }

    /**
     * Checks if the mapping value is numeric and convert it to a float.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     *
     * @return Mapping
     */
    public function float(Mapping $mapping, $message = 'error.float')
    {
        $mapping->addConstraint(new \Mapped\Constraint\Number($message));
        $mapping->transform(new \Mapped\Transformer\Float());
        return $mapping;
    }

    /**
     * Checks if the mapping value is boolean.
     *
     * @param Mapping $mapping The mapping object
     * @param string  $message The error message
     *
     * @return Mapping
     */
    public function boolean(Mapping $mapping, $message = 'error.boolean')
    {
        $mapping->addConstraint(new \Mapped\Constraint\Boolean($message));
        $mapping->transform(new \Mapped\Transformer\Boolean());
        return $mapping;
    }
}
