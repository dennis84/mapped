<?php

namespace Mapped;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Mapping.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Mapping
{
    protected $dispatcher;
    protected $extensions = [];
    protected $transformers = [];
    protected $constraints = [];
    protected $children = [];
    protected $options = [];

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
     * Invokes an extension method. If this method is not defined it will throw
     * an exception.
     *
     * @param string  $method    The called method name
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
            return call_user_func_array([ $extension, $method ], $arguments);
        }

        throw new \BadMethodCallException(
            sprintf('Method "%s" does not exists.', $method));
    }

    /**
     * Sets a new array of child mapping objects.
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
     * @param Mapping $mapping The mapping obejct
     */
    public function addChild($name, Mapping $child)
    {
        $this->children[$name] = $child;
    }

    /**
     * Returns true if a child with given name exists, otherwise false.
     *
     * @param boolean $name The mapping name
     *
     * @return boolean
     */
    public function hasChild($name)
    {
        return array_key_exists($name, $this->children);
    }

    /**
     * Gets a child by name.
     *
     * @param string $name The mapping name.
     *
     * @return Mapping
     *
     * @throws InvalidArgumentException If the child does not exists
     */
    public function getChild($name)
    {
        if (!$this->hasChild($name)) {
            throw new \InvalidArgumentException(sprintf(
                'There is no child with name "%s" registered.', $name));
        }

        return $this->children[$name];
    }

    /**
     * Adds a constraint to the mapping.
     *
     * @param Constraint $constraint The constaint object
     */
    public function addConstraint(Constraint $constraint)
    {
        $this->constraints[] = $constraint;
    }

    /**
     * Sets a new array of constraints.
     *
     * @param Constraint[] $constraints An array of constraints
     */
    public function setConstraints(array $constraints)
    {
        $this->constraints = [];
        foreach ($constraints as $constraint) {
            $this->addConstraint($constraint);
        }
    }

    /**
     * Gets the constraints.
     *
     * @return Constraint[]
     */
    public function getConstraints()
    {
        return $this->constraints;
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
     * Applies the given data to the mapping.
     *
     * @param mixed $data Any data
     */
    public function apply($data)
    {
        if ($this->dispatcher->hasListeners(Events::APPLY)) {
            $event = new Event($this, null, $data);
            $this->dispatcher->dispatch(Events::APPLY, $event);
            $data = $event->getData();
        }

        if ($this->hasChildren()) {
            $result = [];
        } else {
            $result = $data;
        }

        foreach ($this->children as $name => $child) {
            if (isset($data[$name])) {
                $r = $child->apply($data[$name]);
            } else {
                $r = $child->apply(null);
            }

            $result[$name] = $r;
        }

        if ($this->dispatcher->hasListeners(Events::BEFORE_TRANSFORM)) {
            $event = new Event($this, $result, $data);
            $this->dispatcher->dispatch(Events::BEFORE_TRANSFORM, $event);
            $result = $event->getResult();
        }

        foreach ($this->getTransformers() as $transformer) {
            $result = $transformer->transform($result);
        }

        if ($this->dispatcher->hasListeners(Events::APPLIED)) {
            $event = new Event($this, $result, $data);
            $this->dispatcher->dispatch(Events::APPLIED, $event);
            $result = $event->getResult();
        }

        $this->validate($result);

        return $result;
    }

    /**
     * Validates the given data against the constraints.
     *
     * @param mixed $data The data to validate
     *
     * @throws ValidationException
     */
    public function validate($data)
    {
        $errors = [];
        foreach ($this->constraints as $cons) {
            $error = $cons->validate($this, $data);
            if (null !== $error) {
                $errors[] = $error;
            }
        }

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
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
            $data = $event->getResult();
        }

        foreach ($this->getTransformers() as $transformer) {
            $data = $transformer->reverseTransform($data);
        }

        if (!$this->hasChildren()) {
            return $data;
        }

        $value = [];

        foreach ($this->getChildren() as $name => $child) {
            if (isset($data[$name])) {
                $value[$name] = $child->unapply($data[$name]);
            }
        }

        return $value;
    }
}
