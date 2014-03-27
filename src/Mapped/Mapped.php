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

    /**
     * Creates and registers a new mapping object.
     *
     * @param string    $name     The mapping name
     * @param Mapping[] $children An array of mapping objects
     * @param callable  $apply    The apply function
     * @param callable  $unapply  The unapply function
     */
    public function register($name, array $children = [], callable $apply = null, callable $unapply = null)
    {
        $mapping = $this->mapping($children, $apply, $unapply);
        $this->mappings[$name] = $mapping;
    }

    /**
     * Apply.
     *
     * @param mixed  $data
     * @param string $name
     *
     * return mixed
     */
    public function apply($data, $name)
    {
        return $this->get($name)->apply($data);
    }

    /**
     * Unapply.
     *
     * @param mixed  $data
     * @param string $name
     *
     * return mixed
     */
    public function unapply($data, $name)
    {
        return $this->get($name)->unapply($data);
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

        $mapping->transform(new \Mapped\Transformer\Callback($apply, $unapply), -1);
        return $mapping;
    }

    /**
     * Gets a mapping object by name.
     *
     * @param string $name The name
     *
     * @throws InvalidArgumentException If mapping not found
     */
    public function get($name)
    {
        if (false === array_key_exists($name, $this->mappings)) {
            throw new \InvalidArgumentException(sprintf(
                'Mapping for name "%s" does not exists.', $name));
        }

        return $this->mappings[$name];
    }
}
