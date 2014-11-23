<?php

namespace Mapped\Extension;

use Mapped\Mapping;
use Mapped\Events;
use Mapped\ExtensionInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
        $disp    = new EventDispatcher;
        $mapping = new Mapping($disp, $proto->getExtensions());

        $mapping->setOption('prototype', $proto);

        $disp->addListener(Events::APPLY, [$resizer, 'apply']);
        $disp->addListener(Events::UNAPPLY, [$resizer, 'unapply']);

        return $mapping;
    }
}