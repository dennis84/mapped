<?php

namespace Mapped\Extension;

use Mapped\Mapping;
use Mapped\Emitter;
use Mapped\Data;
use Mapped\Events;
use Mapped\ExtensionInterface;

/**
 * Multiple extension.
 */
class Multiple implements ExtensionInterface
{
    /**
     * Makes this mapping to a multiple.
     *
     * @param Mapping $mapping The mapping object
     *
     * @return Mapping
     */
    public function multiple(Mapping $proto)
    {
        $emitter = new Emitter;
        $mapping = new Mapping($emitter, $proto->getExtensions());

        $emitter->on(Events::APPLY, function (Data $data) use ($mapping, $proto) {
            if (null === $data->getInput()) {
                $data->setInput([]);
            }

            $this->resize($mapping, $proto, $data->getInput());
        });

        $emitter->on(Events::UNAPPLY, function (Data $data) use ($mapping, $proto) {
            $this->resize($mapping, $proto, $data->getInput());
        });

        return $mapping;
    }

    /**
     * Adds prototype objects depending on given data.
     *
     * @param Mapping $mapping The mapping object
     * @param Mapping $proto   The prototype mapping object
     * @param mixed   $input   The input
     *
     * @throw InvalidArgumentException If given data is not an array
     */
    private function resize(Mapping $mapping, Mapping $proto, $input)
    {
        if (!is_array($input)) {
            throw new \InvalidArgumentException('The input must be an array.');
        }

        $children = [];
        foreach ($input as $index => $value) {
            $children[(string) $index] = $proto;
        }

        $mapping->setChildren($children);
    }
}
