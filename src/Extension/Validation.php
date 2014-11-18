<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Constraint;
use Mapped\Mapping;
use Mapped\Events;
use Mapped\Event;
use Mapped\Error;

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
        return $mapping->addConstraint(new \Mapped\Constraint\Callback($message, $func));
    }

    /**
     * Adds a constraint.
     *
     * @param Mapping    $mapping    The mapping object
     * @param Constraint $constraint The constaint object
     */
    public function addConstraint(Mapping $mapping, Constraint $cons)
    {
        $disp = $mapping->getDispatcher();
        $disp->addListener(Events::APPLIED, function ($event) use ($cons) {
            if (true !== $cons->check($event->getResult())) {
                $error = new Error($cons->getMessage(), $event->getPropertyPath());
                $event->addError($error);
            }
        });

        return $mapping;
    }
}
