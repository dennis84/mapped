<?php

namespace Mapped;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * This is a helper to build mapping objects.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Mapped
{
    protected $extensions = [];
    protected $mappings = [];

    /**
     * Constructor.
     *
     * @param ExtensionInterface[] $extension An array of extensions
     */
    public function __construct(array $extensions = [])
    {
        $this->extensions = array_merge([
            new \Mapped\Extension\Constraints(),
            new \Mapped\Extension\Optional(),
            new \Mapped\Extension\Multiple(),
            new \Mapped\Extension\Verifying(),
        ], $extensions);
    }

    public function register($type, array $children = [], callable $apply = null, callable $unapply = null)
    {
        $mapping = $this->create('', $children, $apply, $unapply);
        $this->mappings[$type] = $mapping;
    }

    public function apply($data, $type)
    {
        return $this->getMapping($type)->apply($data);
    }

    public function unapply($data, $type)
    {
        return $this->getMapping($type)->unapply($data);
    }

    /**
     * @param string    $name     The mapping name
     * @param Mapping[] $children An array of mapping objects
     * @param callable  $apply    The apply function
     * @param callable  $unapply  The unapply function
     *
     * @return Mapping
     */
    public function create($name, array $children = [], callable $apply = null, callable $unapply = null)
    {
        $mapping = new Mapping($name, new EventDispatcher(), $this->extensions);

        foreach ($children as $child) {
            $child->setParent($mapping);
            $mapping->addChild($child);
        }

        $mapping->transform(new \Mapped\Transformer\Callback($apply, $unapply), -1);
        return $mapping;
    }

    protected function getMapping($type)
    {
        if (false === array_key_exists($type, $this->mappings)) {
            throw new \InvalidArgumentException(sprintf(
                'Mapping for type "%s" does not exists.', $type));
        }

        return $this->mappings[$type];
    }
}
