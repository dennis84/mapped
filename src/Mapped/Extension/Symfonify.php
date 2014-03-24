<?php

namespace Mapped\Extension;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ValidatorInterface;
use Mapped\ExtensionInterface;
use Mapped\Error;
use Mapped\Event;
use Mapped\Events;
use Mapped\Mapping;

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
        // Activates annotation validation
        if (null !== $this->validator) {
            $disp = $mapping->getDispatcher();
            $disp->addListener(Events::APPLIED, function (Event $event) {
                $data = $event->getData();
                if (!is_object($data)) {
                    return;
                }

                $violations = $this->validator->validate($data);

                foreach ($violations as $vio) {
                    $mapping = $event->getMapping();

                    if ($mapping->hasChild($vio->getPropertyPath())) {
                        $mapping = $mapping->getChild($vio->getPropertyPath());
                    }

                    // thow exceptions ...
                }
            });
        }
    }
}
