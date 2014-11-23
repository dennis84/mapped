<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Mapping;
use Mapped\Events;
use Mapped\Event;
use Mapped\Error;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PropertyAccess\PropertyPath;

/**
 * This extension allows you to use Symfony's validation constraints.
 */
class SymfonyValidation implements ExtensionInterface
{
    protected $validator;

    /**
     * Constructor.
     *
     * @param ValidatorInterface $validator The symfony validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Adds a symfony constraint.
     *
     * @param Mapping    $mapping    The mapping object
     * @param Constraint $constraint A symfony constraint
     *
     * @return Mapping
     */
    public function assert(Mapping $mapping, Constraint $cons)
    {
        $disp = $mapping->getDispatcher();
        $disp->addListener(Events::APPLIED, function (Event $event) use ($cons) {
            $vios = $this->validator->validate($event->getResult(), $cons);
            foreach ($vios as $vio) {
                $event->addError(new Error(
                    $vio->getMessage(),
                    $event->getPropertyPath()
                ));
            }
        });

        return $mapping;
    }

    /**
     * Enables object validation.
     *
     * @param Mapping $mapping The mapping object
     *
     * @return Mapping
     */
    public function enableObjectValidation(Mapping $mapping)
    {
        $disp = $mapping->getDispatcher();
        $disp->addListener(Events::APPLIED, function (Event $event) {
            $vios = $this->validator->validate($event->getResult());
            foreach ($vios as $vio) {
                $propertyPath = new PropertyPath($vio->getPropertyPath());
                $event->addError(new Error(
                    $vio->getMessage(),
                    $propertyPath->getElements()
                ));
            }
        });

        return $mapping;
    }
}
