<?php

namespace Mapped;

/**
 * This is a helper to build mapping objects.
 */
class Factory
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
            new Extension\Transform,
            new Extension\TransformTo,
            new Extension\Validation,
            new Extension\Multiple,
            new Extension\Optional,
            new Extension\Ignored,
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
        $mapping = new Mapping(new Emitter, $this->extensions);

        foreach ($children as $name => $child) {
            $mapping->addChild($name, $child);
        }

        $mapping->transform(new Transformer\Callback($apply, $unapply));
        return $mapping;
    }

    /**
     * Creates a string mapping.
     *
     * @param string $message The error message
     *
     * @return Mapping
     */
    public function string($message = 'error.string')
    {
        $mapping = new Mapping(new Emitter, $this->extensions);
        $mapping->transform(new Transformer\String);
        $mapping->validate(new Constraint\Type($message, 'string'));
        return $mapping;
    }

    /**
     * Creates an int mapping.
     *
     * @param string $message The error message
     *
     * @return Mapping
     */
    public function int($message = 'error.int')
    {
        $mapping = new Mapping(new Emitter, $this->extensions);
        $mapping->transform(new Transformer\Int);
        $mapping->validate(new Constraint\Type($message, 'int'));
        return $mapping;
    }

    /**
     * Creates a float mapping.
     *
     * @param string $message The error message
     *
     * @return Mapping
     */
    public function float($message = 'error.float')
    {
        $mapping = new Mapping(new Emitter, $this->extensions);
        $mapping->transform(new Transformer\Float);
        $mapping->validate(new Constraint\Type($message, 'float'));
        return $mapping;
    }

    /**
     * Creates a bool mapping.
     *
     * @param string $message The error message
     *
     * @return Mapping
     */
    public function bool($message = 'error.bool')
    {
        $mapping = new Mapping(new Emitter, $this->extensions);
        $mapping->transform(new Transformer\Bool);
        $mapping->validate(new Constraint\Type($message, 'bool'));
        return $mapping;
    }
}
