<?php

namespace Mapped\Extension;

use Mapped\Mapping;
use Mapped\Event;
use Mapped\Events;
use Mapped\ExtensionInterface;

/**
 * Optional.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Optional implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function initialize(Mapping $mapping)
    {
    }

    /**
     * Makes this mapping to an optional.
     *
     * @param Mapping $mapping The mapping object
     *
     * @return Mapping
     */
    public function optional(Mapping $mapping)
    {
        $disp = $mapping->getDispatcher();
        $disp->addListener(Events::BEFORE_TRANSFORM, function (Event $event) {
            if (null === $event->getData()) {
                $event->setResult(null);
            }
        });

        return $mapping;
    }
}
