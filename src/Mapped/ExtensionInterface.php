<?php

namespace Mapped;

/**
 * ExtensionInterface.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
interface ExtensionInterface
{
    /**
     * This method is called when a new mapping is created.
     *
     * @param Mapping $mapping The mapping object
     */
    function initialize(Mapping $mapping);
}
