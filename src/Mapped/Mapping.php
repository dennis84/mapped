<?php

namespace Mapped;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Mapping.
 */
class Mapping
{
    protected $dispatcher;
    protected $extensions = [];
    protected $transformers = [];
    protected $constraints = [];
    protected $children = [];
    protected $options = [];
    protected $optional = false;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher The event dispatcher
     * @param ExtensionInterface[]     $extensions An array of extensions
     */
    public function __construct(EventDispatcherInterface $dispatcher, array $extensions = [])
    {
        $this->dispatcher = $dispatcher;
        $this->extensions = $extensions;
        foreach ($extensions as $extension) {
            $extension->initialize($this);
        }
    }

    /**
     * Returns all registered extensions.
     *
     * @return ExtensionInterface[]
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Adds a transformer.
     *
     * @param Transformer $transformer The transformer
     * @param int         $prio        The transformation priority
     */
    public function transform(Transformer $transformer, $prio = 0)
    {
        $this->transformers[$prio][] = $transformer;
        return $this;
    }

    /**
     * Returns the transformers sorted by priority.
     *
     * @return Transformer[]
     */
    public function getTransformers()
    {
        $transformers = $this->transformers;
        if (0 === count($transformers)) {
            return $transformers;
        }

        krsort($transformers);
        return call_user_func_array('array_merge', $transformers);
    }

    /**
     * Invokes an extension method.
     *
     * @param string  $method    The method name
     * @param mixed[] $arguments The method arguments
     *
     * @return Mapping
     *
     * @throws BadMethodCallException If method is not callable
     */
    public function __call($method, $arguments)
    {
        if ('initialize' === $method) {
            return;
        }

        foreach ($this->extensions as $extension) {
            if (false === method_exists($extension, $method)) {
                continue;
            }

            array_unshift($arguments, $this);
            return call_user_func_array([$extension, $method], $arguments);
        }

        throw new \BadMethodCallException(
            sprintf('Method "%s" does not exists.', $method));
    }

    /**
     * Sets a new array of child mappings.
     *
     * @param Mapping[] $children An array of mapping objects
     */
    public function setChildren(array $children)
    {
        $this->children = [];
        foreach ($children as $name => $child) {
            $this->addChild($name, $child);
        }
    }

    /**
     * Returns true if the mapping has children, otherwise false.
     *
     * @return boolean
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * Gets the child mappings.
     *
     * @return Mapping[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Adds a child mapping.
     *
     * @param string  $name    The mapping name
     * @param Mapping $mapping The mapping object
     */
    public function addChild($name, Mapping $child)
    {
        $this->children[$name] = $child;
    }

    /**
     * Adds a constraint.
     *
     * @param Constraint $constraint The constaint object
     */
    public function addConstraint(Constraint $constraint)
    {
        $this->constraints[] = $constraint;
    }

    /**
     * Gets the event dispatcher.
     *
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Sets an option.
     *
     * @param string $name  The option name
     * @param mixed  $value The option value
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * Gets an option.
     *
     * @param string $name The option name
     *
     * @return mixed
     *
     * @throws InvalidArgumentException If option does not exists
     */
    public function getOption($name)
    {
        if (!array_key_exists($name, $this->options)) {
            throw new \InvalidArgumentException(sprintf(
                'Option with name "%s" does not exists.', $name));
        }

        return $this->options[$name];
    }

    /**
     * Returns true if an option with given name exists, otherwise false.
     *
     * @param boolean $name The option name
     *
     * @return boolean
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * Makes this mapping optional.
     *
     * @return Mapping
     */
    public function optional()
    {
        $this->optional = true;
        return $this;
    }

    /**
     * Returns true if the mapping is optional, otherwise false.
     *
     * @return boolean
     */
    public function isOptional()
    {
        return $this->optional;
    }

    /**
     * Applies the given data to the mapping.
     *
     * @param mixed $data Any data
     *
     * @return mixed
     *
     * @throw ValidationException If the validation failed
     */
    public function apply($data)
    {
        $result = $this->doApply($data);

        if (count($result->getErrors()) > 0) {
            throw new ValidationException($result->getErrors());
        }

        return $result->getData();
    }

    /**
     * Unapply the given data.
     *
     * @param mixed $data The data to unapply
     */
    public function unapply($data)
    {
        if ($this->dispatcher->hasListeners(Events::UNAPPLY)) {
            $event = new Event($this, $data);
            $this->dispatcher->dispatch(Events::UNAPPLY, $event);
            $data = $event->getData();
        }

        foreach ($this->getTransformers() as $transformer) {
            $data = $transformer->reverseTransform($data);
        }

        if (!$this->hasChildren()) {
            return $data;
        }

        $result = [];

        foreach ($this->getChildren() as $name => $child) {
            if (is_array($data) && array_key_exists($name, $data)) {
                $result[$name] = $child->unapply($data[$name]);
            }
        }

        return $result;
    }

    /**
     * The recursive apply call.
     *
     * @param mixed $data         Any data
     * @param array $propertyPath The property path
     *
     * @return MappingResult
     */
    private function doApply($data, array $propertyPath = [])
    {
        if ($this->dispatcher->hasListeners(Events::APPLY)) {
            $event = new Event($this, $data, null, [], $propertyPath);
            $this->dispatcher->dispatch(Events::APPLY, $event);
            $data = $event->getData();
        }

        $errors = [];

        if ($this->hasChildren()) {
            $result = [];
        } else {
            $result = $data;
        }

        foreach ($this->children as $name => $child) {
            $childPath = array_merge($propertyPath, [$name]);

            if (is_array($data) && array_key_exists($name, $data)) {
                $childResult = $child->doApply($data[$name], $childPath);
                $result[$name] = $childResult->getData();
                $errors = array_merge($errors, $childResult->getErrors());
            } elseif ($child->isOptional()) {
                $result[$name] = null;
            } else {
                $errors[] = new Error('error.required', $childPath);
            }
        }

        foreach ($this->getTransformers() as $transformer) {
            $result = $transformer->transform($result);
        }

        foreach ($this->constraints as $cons) {
            if (true !== $cons->check($result)) {
                $error = new Error($cons->getMessage(), $propertyPath);
                array_unshift($errors, $error);
            }
        }

        if ($this->dispatcher->hasListeners(Events::APPLIED)) {
            $event = new Event($this, $data, $result, $errors, $propertyPath);
            $this->dispatcher->dispatch(Events::APPLIED, $event);
            $result = $event->getResult();
            $errors = $event->getErrors();
        }

        return new MappingResult($result, $errors);
    }
}
