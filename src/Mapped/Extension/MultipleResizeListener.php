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
     * On bind.
     *
     * @param Event $event The event
     */
    public function bind(Event $event)
    {
        if (null === $event->getData()) {
            $event->setData([]);
        }

        $this->prepare($event->getMapping(), $event->getData());
    }

    /**
     * On fill.
     *
     * @param Event $event The event
     */
    public function fill(Event $event)
    {
        $this->prepare($event->getMapping(), $event->getResult());
    }

    /**
     * Prepares the multiple mapping.
     *
     * @param Mapping $mapping The mapping object
     * @param mixed   $data    The data
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

            $clone->setName((string) $index);
            $clone->setParent($mapping);

            foreach ($clone->getChildren() as $child) {
                $child->setParent($clone);
            }

            $clones[] = $clone;
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
