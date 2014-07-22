<?php

namespace Mapped;

/**
 * Extension.
 */
abstract class Extension
{
    /**
     * This method is called when a new mapping is created.
     *
     * @param Mapping $mapping The mapping object
     */
    public function initialize(Mapping $mapping)
    {
    }
}
