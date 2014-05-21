<?php

namespace Mapped;

/**
 * MappingCollectionFactory.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class MappingCollectionFactory
{
    /**
     * Creates a mapping collection.
     *
     * @param MappingCreatorInterface[] $creators An array of mapping creators
     *
     * @return MappingCollection
     */
    public function create(array $creators = [])
    {
        $coll = new MappingCollection();
        $factory = new MappingFactory();

        foreach ($creators as $creator) {
            $coll->add($creator->getName(), $creator->create($factory));
        }

        return $coll;
    }
}
