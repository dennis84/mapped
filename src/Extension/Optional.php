<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Mapping;
use Mapped\Events;
use Mapped\Data;

/**
 * Optional.
 */
class Optional implements ExtensionInterface
{
    /**
     * Makes this mapping optional.
     *
     * @param Mapping $mapping The mapping object
     *
     * @return Mapping
     */
    public function optional(Mapping $mapping)
    {
        $emitter = $mapping->getEmitter();
        $emitter->on(Events::APPLIED, function (Data $data) {
            if (0 === count($data->getErrors())) {
                return;
            }

            foreach ($data->getErrors() as $error) {
                if ('error.required' === $error->getMessage()) {
                    $data->removeError($error);
                }
            }
        });

        return $mapping;
    }
}
