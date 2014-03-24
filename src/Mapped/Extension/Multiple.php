<?php

namespace Mapped\Extension;

use Mapped\Mapping;
use Mapped\Events;
use Mapped\ExtensionInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Multiple extension.
 *
 * @author Dennis Dietrich <d.dietrich84@googlemail.com>
 */
class Multiple implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function initialize(Mapping $mapping)
    {
    }

    /**
     * Makes this mapping to a multiple.
     *
     * @param Mapping $mapping The mapping object
     *
     * @return Mapping
     */
    public function multiple(Mapping $proto)
    {
        $resizer = new MultipleResizeListener();

        $name    = $proto->getName();
        $disp    = new EventDispatcher();
        $mapping = new Mapping($name, $disp, $proto->getExtensions());
        $mapping->setOption('prototype', $proto);

        $disp->addListener(Events::APPLY, [ $resizer, 'bind' ]);
        $disp->addListener(Events::UNAPPLY, [ $resizer, 'fill' ]);

        return $mapping;
    }
}
