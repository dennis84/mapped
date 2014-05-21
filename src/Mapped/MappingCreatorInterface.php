<?php

namespace Mapped;

/**
 * MappingCreatorInterface.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
interface MappingCreatorInterface
{
    /**
     * Create.
     *
     * @param MappingFactory $factory The mapping factory
     *
     * @return Mapping
     */
    function create(MappingFactory $factory);

    /**
     * Gets the name.
     *
     * @return string
     */
    function getName();
}
