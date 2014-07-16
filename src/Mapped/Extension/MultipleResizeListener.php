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

        $clones = [];
        foreach ($data as $index => $value) {
            $proto = $mapping->getOption('prototype');
            $clone = $this->cloneMapping($proto);

            $clones[(string) $index] = $clone;
        }

        $mapping->setChildren($clones);
    }

    /**
     * Clones a mapping object.
     *
     * @param Mapping $mapping The mapping object
     *
     * @return Mapping The cloned mapping object
     */
    protected function cloneMapping(Mapping $mapping)
    {
        $clone = clone $mapping;

        $clone->setChildren(array_map(function ($child) {
            return $this->cloneMapping($child);
        }, $mapping->getChildren()));

        $clone->setConstraints(array_map(function ($constraint) {
            return clone $constraint;
        }, $mapping->getConstraints()));

        return $clone;
    }
}
