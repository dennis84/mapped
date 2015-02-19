<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Mapping;
use Mapped\Constraint;
use Mapped\Events;
use Mapped\Error;
use Mapped\Data;

/**
 * Validation extension.
 */
class Validation implements ExtensionInterface
{
    /**
     * Adds a constraint.
     *
     * @param Mapping    $mapping    The mapping object
     * @param Constraint $constraint The constaint object
     *
     * @return Mapping
     */
    public function validate(Mapping $mapping, Constraint $cons)
    {
        $emitter = $mapping->getEmitter();
        $emitter->on(Events::APPLIED, function (Data $data) use ($cons) {
            if (count($data->getErrors()) > 0) {
                return;
            }

            if (false === $cons->check($data->getResult())) {
                $error = new Error($cons->getMessage(), $data->getPropertyPath());
                $data->addError($error);
            }
        });

        return $mapping;
    }

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
