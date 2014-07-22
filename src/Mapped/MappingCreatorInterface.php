<?php

namespace Mapped;

/**
 * MappingCreatorInterface.
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
