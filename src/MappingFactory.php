<?php

namespace Mapped;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * This is a helper to build mapping objects.
 */
class MappingFactory
{
    protected $extensions = [];

    /**
     * Constructor.
     *
     * @param ExtensionInterface[] $extension An array of extensions
     */
    public function __construct(array $extensions = [])
    {
        $this->extensions = array_merge([
            new \Mapped\Extension\Transform,
            new \Mapped\Extension\Validation,
            new \Mapped\Extension\Constraints,
            new \Mapped\Extension\Multiple,
        ], $extensions);
    }

    /**
     * Creates a mapping object.
     *
     * @param Mapping[] $children An array of mapping objects
     * @param callable  $apply    The apply function
     * @param callable  $unapply  The unapply function
     *
     * @return Mapping
     */
    public function mapping(array $children = [], callable $apply = null, callable $unapply = null)
    {
        $mapping = new Mapping(new EventDispatcher, $this->extensions);

        foreach ($children as $name => $child) {
            $mapping->addChild($name, $child);
        }

        $mapping->transform(new \Mapped\Transformer\Callback($apply, $unapply));
        return $mapping;
    }
}
