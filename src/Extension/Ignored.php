<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Mapping;
use Mapped\Events;
use Mapped\Data;

/**
 * Ignored.
 */
class Ignored implements ExtensionInterface
{
    /**
     * Makes this mapping to an ignored field.
     *
     * @param Mapping $mapping The mapping object
     * @param mixed   $value   The value
     */
    public function ignored(Mapping $mapping, $value)
    {
        $emitter = $mapping->getEmitter();
        $emitter->on(Events::APPLIED, function (Data $data) use ($value) {
            $data->setErrors([]);
            $data->setResult($value);
        });

        return $mapping;
    }
}
