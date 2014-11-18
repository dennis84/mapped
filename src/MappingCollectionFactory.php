<?php

namespace Mapped;

/**
 * MappingCollectionFactory.
 */
class MappingCollectionFactory
{
    /**
     * Creates a mapping collection.
     *
     * @param MappingCreatorInterface[] $creators   An array of mapping creators
     * @param ExtensionInterface[]      $extensions An array of extensions
     *
     * @return MappingCollection
     */
    public function create(array $creators = [], array $extensions = [])
    {
        $coll = new MappingCollection;
        $factory = new MappingFactory($extensions);

        foreach ($creators as $creator) {
            $coll->add($creator->getName(), $creator->create($factory));
        }

        return $coll;
    }
}
