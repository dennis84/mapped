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
     * Invokes an extension method.
     *
     * @param string  $method The method name
     * @param mixed[] $args   The method arguments
     *
     * @return Mapping
     *
     * @throws BadMethodCallException If method is not callable
     */
    public function __call($method, $args)
    {
        foreach ($this->extensions as $extension) {
            if (method_exists($extension, $method)) {
                array_unshift($args, $this);
                return call_user_func_array([$extension, $method], $args);
            }
        }

        throw new \BadMethodCallException(sprintf('Method "%s" does not exists.', $method));
    }

    /**
     * Sets a new array of child mappings.
     *
     * @param Mapping[] $children An array of mapping objects
     */
    public function setChildren(array $children)
    {
        $this->children = $children;
    }

    /**
     * Returns true if the mapping has children, otherwise false.
     *
     * @return bool
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
        if (!$this->hasOption($name)) {
            throw new \InvalidArgumentException(sprintf('Option with name "%s" does not exist.', $name));
        }

        return $this->options[$name];
    }

    /**
     * Returns true if an option with given name exists, otherwise false.
     *
     * @param bool $name The option name
     *
     * @return bool
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
     * @return bool
     */
    public function isOptional()
    {
        return $this->optional;
    }

    /**
     * Applies the given data to the mapping.
     *
     * @param mixed    $data Any data
     * @param callable $func Callback function to transform the final data
     *
     * @return mixed
     *
     * @throw ValidationException If the validation failed
     */
    public function apply($data, callable $func = null)
    {
        $result = $this->doApply($data, [], $func);

        if (count($result->getErrors()) > 0) {
            throw new ValidationException($result->getErrors());
        }

        return $result->getData();
    }

    /**
     * Unapply the given data.
     *
     * @param mixed    $data The data to unapply
     * @param callable $func Callback function to transform the final data
     *
     * @return mixed
     */
    public function unapply($data, callable $func = null)
    {
        if (null !== $func) {
            $this->dispatcher->addListener(Events::UNAPPLY, function ($event) use ($func) {
                $trans = new Transformer\Callback(null, $func);
                $event->setData($trans->reverseTransform($event->getData()));
            });
        }

        if ($this->dispatcher->hasListeners(Events::UNAPPLY)) {
            $event = new Event($this, $data);
            $this->dispatcher->dispatch(Events::UNAPPLY, $event);
            $data = $event->getData();
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
     * @param mixed    $data         Any data
     * @param array    $propertyPath The property path
     * @param callable $func         Callback function to transform the final data
     *
     * @return MappingResult
     */
    private function doApply($data, array $propertyPath = [], callable $func = null)
    {
        if (null !== $func) {
            $this->dispatcher->addListener(Events::APPLIED, function ($event) use ($func) {
                $trans = new Transformer\Callback($func);
                $event->setResult($trans->transform($event->getResult()));
            });
        }

        if ($this->dispatcher->hasListeners(Events::APPLY)) {
            $event = new Event($this, $data, null, [], $propertyPath);
            $this->dispatcher->dispatch(Events::APPLY, $event);
            $data = $event->getData();
        }

        $errors = [];
        $result = $data;

        if ($this->hasChildren()) {
            $result = [];
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

        if ($this->dispatcher->hasListeners(Events::APPLIED)) {
            $event = new Event($this, $data, $result, $errors, $propertyPath);
            $this->dispatcher->dispatch(Events::APPLIED, $event);
            $result = $event->getResult();
            $errors = $event->getErrors();
        }

        return new MappingResult($result, $errors);
    }
}
