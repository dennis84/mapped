<?php

namespace Mapped\Extension;

use Mapped\Mapping;
use Mapped\Emitter;
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
        $resizer = new MultipleResizeListener;
        $emitter = new Emitter;
        $mapping = new Mapping($emitter, $proto->getExtensions());

        $mapping->setOption('prototype', $proto);

        $emitter->on(Events::APPLY, [$resizer, 'apply']);
        $emitter->on(Events::UNAPPLY, [$resizer, 'unapply']);

        return $mapping;
    }
}
