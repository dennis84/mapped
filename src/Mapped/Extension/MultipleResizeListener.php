<?php

namespace Mapped\Extension;

use Mapped\Event;
use Mapped\Mapping;

/**
 * MultipleResizeListener.
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

        $this->resize($event->getMapping(), $event->getData());
    }

    /**
     * On unapply.
     *
     * @param Event $event The event
     */
    public function unapply(Event $event)
    {
        $this->resize($event->getMapping(), $event->getData());
    }

    /**
     * Adds prototype objects depending on given data.
     *
     * @param Mapping $mapping The mapping object
     * @param mixed   $data    The data
     *
     * @throw InvalidArgumentException If given data is not an array
     */
    protected function resize(Mapping $mapping, $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('The data must be an array.');
        }

        $children = [];
        foreach ($data as $index => $value) {
            $children[(string) $index] = $mapping->getOption('prototype');
        }

        $mapping->setChildren($children);
    }
}
