<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Transformer;
use Mapped\Mapping;
use Mapped\Events;

/**
 * Transform extension.
 */
class Transform implements ExtensionInterface
{
    /**
     * Adds a transformer.
     *
     * @param Mapping     $mapping     The mapping object
     * @param Transformer $transformer The transformer
     *
     * @return Mapping
     */
    public function transform(Mapping $mapping, Transformer $transformer)
    {
        $disp = $mapping->getDispatcher();

        $disp->addListener(Events::APPLIED, function ($event) use ($transformer) {
            if (count($event->getErrors()) > 0) {
                $event->stopPropagation();
                return;
            }

            $event->setResult($transformer->transform($event->getResult()));
        });

        $disp->addListener(Events::UNAPPLY, function ($event) use ($transformer) {
            $event->setData($transformer->reverseTransform($event->getData()));
        });

        return $mapping;
    }
}
