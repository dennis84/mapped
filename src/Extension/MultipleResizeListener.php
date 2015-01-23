<?php

namespace Mapped\Extension;

use Mapped\Data;
use Mapped\Mapping;

/**
 * MultipleResizeListener.
 */
class MultipleResizeListener
{
    /**
     * On apply.
     *
     * @param Event   $event   The data
     * @param Mapping $mapping The mapping object
     */
    public function apply(Data $data, Mapping $mapping)
    {
        if (null === $data->getInput()) {
            $data->setInput([]);
        }

        $this->resize($mapping, $data->getInput());
    }

    /**
     * On unapply.
     *
     * @param Data    $data    The data
     * @param Mapping $mapping The mapping object
     */
    public function unapply(Data $data, Mapping $mapping)
    {
        $this->resize($mapping, $data->getInput());
    }

    /**
     * Adds prototype objects depending on given data.
     *
     * @param Mapping $mapping The mapping object
     * @param mixed   $input   The input
     *
     * @throw InvalidArgumentException If given data is not an array
     */
    protected function resize(Mapping $mapping, $input)
    {
        if (!is_array($input)) {
            throw new \InvalidArgumentException('The input must be an array.');
        }

        $children = [];
        foreach ($input as $index => $value) {
            $children[(string) $index] = $mapping->getOption('prototype');
        }

        $mapping->setChildren($children);
    }
}
