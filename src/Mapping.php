<?php

namespace Mapped;

/**
 * Mapping.
 */
class Mapping
{
    private $emitter;
    private $extensions = [];
    private $children = [];

    /**
     * Constructor.
     *
     * @param Emitter              $emitter    The event emitter
     * @param ExtensionInterface[] $extensions An array of extensions
     */
    public function __construct(Emitter $emitter, array $extensions = [])
    {
        $this->emitter = $emitter;
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
     * Gets the event emitter.
     *
     * @return Emitter
     */
    public function getEmitter()
    {
        return $this->emitter;
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
        $data = new Data($data);
        $this->doApply($data);

        if (count($data->getErrors()) > 0) {
            throw new ValidationException($data->getErrors());
        }

        return $data->getResult();
    }

    /**
     * Unapply the given data.
     *
     * @param mixed $data The data to unapply
     *
     * @return mixed
     */
    public function unapply($input)
    {
        $data = new Data($input);
        $this->emitter->emit(Events::UNAPPLY, $data, $this);
        $result = $input = $data->getInput();

        if ($this->hasChildren()) {
            $result = [];
            foreach ($this->getChildren() as $name => $child) {
                if (is_array($input) && array_key_exists($name, $input)) {
                    $result[$name] = $child->unapply($input[$name]);
                }
            }
        }

        $data->setResult($result);
        $this->emitter->emit(Events::UNAPPLIED, $data, $this);

        return $data->getResult();
    }

    /**
     * The recursive apply call.
     *
     * @param Data $data The data
     */
    private function doApply(Data $data)
    {
        $this->emitter->emit(Events::APPLY, $data, $this);

        if (count($data->getErrors()) > 0) {
            $data->setResult(null);
            $this->emitter->emit(Events::APPLIED, $data, $this);
            return;
        }

        $errors = $data->getErrors();
        $result = $input = $data->getInput();

        if ($this->hasChildren()) {
            $result = [];

            foreach ($this->children as $name => $child) {
                $path = array_merge($data->getPropertyPath(), [$name]);
                $childData = new Data(null, null, [], $path);

                if (is_array($input) && array_key_exists($name, $input)) {
                    $childData->setInput($input[$name]);
                } else {
                    $childData->addError(new Error('error.required', $path));
                }

                $child->doApply($childData);
                $result[$name] = $childData->getResult();
                $errors = array_merge($errors, $childData->getErrors());
            }
        }

        $data->setErrors($errors);
        $data->setResult($result);
        $this->emitter->emit(Events::APPLIED, $data, $this);
    }
}
