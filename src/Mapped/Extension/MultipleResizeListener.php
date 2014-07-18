<?php

namespace Mapped\Extension;

use Mapped\Event;
use Mapped\Mapping;

/**
 * MultipleResizeListener.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class MultipleResizeListener
{
    /**
     * On apply.
     *
     * @param Event $event The event
     */
    public function apply(Event $event)
    {
        if (null === $event->getData()) {
            $event->setData([]);
        }

        $this->prepare($event->getMapping(), $event->getData());
    }

    /**
     * On unapply.
     *
     * @param Event $event The event
     */
    public function unapply(Event $event)
    {
        $this->prepare($event->getMapping(), $event->getResult());
    }

    /**
     * Prepares the multiple mapping.
     *
     * @param Mapping $mapping The mapping object
     * @param mixed   $data    The data
     *
     * @throw InvalidArgumentException If given data is not an array
     */
    protected function prepare(Mapping $mapping, $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('');
        }

        $children = [];
        foreach ($data as $index => $value) {
            $children[(string) $index] = $mapping->getOption('prototype');
        }

        $mapping->setChildren($children);
    }
}
