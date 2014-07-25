<?php

namespace Mapped\Extension;

use Mapped\Extension;
use Mapped\Mapping;
use Mapped\Events;
use Mapped\Event;
use Mapped\Error;
use Mapped\Constraint\Callback;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * This extension allows you to use Symfony's validation constraints.
 */
class SymfonyValidation extends Extension
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
            $vios = $this->validator->validateValue($event->getResult(), $cons);
            if (count($vios) > 0) {
                $errors = $event->getErrors();
                foreach ($vios as $vio) {
                    $errors[] = new Error(
                        $vio->getMessage(),
                        $event->getPropertyPath()
                    );
                }

                $event->setErrors($errors);
            }
        });

        return $mapping;
    }
}
