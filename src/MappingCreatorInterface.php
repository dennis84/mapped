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
    public function create(MappingFactory $factory);

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName();
}
