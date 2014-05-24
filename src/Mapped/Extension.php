<?php

namespace Mapped;

/**
 * Extension.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
abstract class Extension
{
    /**
     * This method is called when a new mapping is created.
     *
     * @param Mapping $mapping The mapping object
     */
    function initialize(Mapping $mapping)
    {
    }
}
