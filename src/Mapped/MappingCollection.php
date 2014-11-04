<?php

namespace Mapped;

/**
 * MappingCollection.
 */
class MappingCollection
{
    protected $mappings = [];

    /**
     * Constructor.
     *
     * @param Mapping[] $mappings An array of mapping objects
     */
    public function __construct(array $mappings = [])
    {
        $this->mappings = $mappings;
    }

    /**
     * Adds a mapping object.
     *
     * @param string  $name    The mapping name
     * @param Mapping $mapping The mapping object
     */
    public function add($name, Mapping $mapping)
    {
        $this->mappings[$name] = $mapping;
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
                'A Mapping with name "%s" does not exists.', $name));
        }

        return $this->mappings[$name];
    }

    /**
     * Apply.
     *
     * @param string   $name
     * @param mixed    $data
     * @param callable $func
     *
     * return mixed
     */
    public function apply($name, $data, callable $func = null)
    {
        return $this->get($name)->apply($data, $func);
    }

    /**
     * Unapply.
     *
     * @param string   $name
     * @param mixed    $data
     * @param callable $func
     *
     * return mixed
     */
    public function unapply($name, $data, callable $func = null)
    {
        return $this->get($name)->unapply($data, $func);
    }
}
