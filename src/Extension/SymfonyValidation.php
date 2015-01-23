<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Mapping;
use Mapped\Events;
use Mapped\Data;
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
     * @param Mapping    $mapping The mapping object
     * @param Constraint $cons    The constraint to validate
     * @param array|null $groups  The validation groups
     *
     * @return Mapping
     */
    public function assert(Mapping $mapping, Constraint $cons, $groups = null)
    {
        $emitter = $mapping->getEmitter();
        $emitter->on(Events::APPLIED, function (Data $data) use ($cons, $groups) {
            $vios = $this->validator->validate($data->getResult(), $cons, $groups);
            foreach ($vios as $vio) {
                $data->addError(new Error(
                    $vio->getMessage(),
                    $data->getPropertyPath()
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
        $emitter = $mapping->getEmitter();
        $emitter->on(Events::APPLIED, function (Data $data) {
            $vios = $this->validator->validate($data->getResult());
            foreach ($vios as $vio) {
                $propertyPath = new PropertyPath($vio->getPropertyPath());
                $data->addError(new Error(
                    $vio->getMessage(),
                    $propertyPath->getElements()
                ));
            }
        });

        return $mapping;
    }
}
