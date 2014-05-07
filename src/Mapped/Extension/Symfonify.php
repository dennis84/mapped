<?php

namespace Mapped\Extension;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ValidatorInterface;
use Mapped\ExtensionInterface;
use Mapped\Error;
use Mapped\Event;
use Mapped\Events;
use Mapped\Mapping;
use Mapped\ValidationException;

/**
 * Symfonify.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Symfonify implements ExtensionInterface
{
    protected $validator;

    /**
     * Constructor.
     *
     * @param ValidatorInterface|null $validator The symfony validator
     */
    public function __construct(ValidatorInterface $validator = null)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(Mapping $mapping)
    {
        if (null === $this->validator) {
            return;
        }

        $disp = $mapping->getDispatcher();
        $disp->addListener(Events::APPLIED, function (Event $event) {
            $data = $event->getResult();
            if (!is_object($data)) {
                return;
            }

            $violations = $this->validator->validate($data);
            $errors = [];

            foreach ($violations as $vio) {
                $mapping = $event->getMapping();

                if ($mapping->hasChild($vio->getPropertyPath())) {
                    $mapping = $mapping->getChild($vio->getPropertyPath());
                }

                $errors[] = new Error($mapping, $vio->getMessage());
            }

            if (count($errors) > 0) {
                throw new ValidationException($errors);
            }
        });
    }
}
