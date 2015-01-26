<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Mapping;
use Mapped\Transformer;
use Mapped\Events;
use Mapped\Data;

/**
 * Transform.
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
        $emitter = $mapping->getEmitter();

        $emitter->on(Events::APPLIED, function (Data $data) use ($transformer) {
            if (count($data->getErrors()) > 0) {
                return;
            }

            $data->setResult($transformer->transform($data->getResult()));
        });

        $emitter->on(Events::UNAPPLY, function (Data $data) use ($transformer) {
            $data->setInput($transformer->reverseTransform($data->getInput()));
        });

        return $mapping;
    }
}
