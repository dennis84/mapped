<?php

namespace Mapped;

/**
 * Mapping.
 */
class Mapping
{
    protected $emitter;
    protected $extensions = [];
    protected $children = [];
    protected $options = [];

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
     * Adds a transformer.
     *
     * @param Transformer $transformer The transformer
     *
     * @return Mapping
     */
    public function transform(Transformer $transformer)
    {
        $emitter = $this->emitter;

        $emitter->on(Events::APPLIED, function (Data $data) use ($transformer) {
            if (count($data->getErrors()) > 0) {
                return;
            }

            $data->setResult($transformer->transform($data->getResult()));
        });

        $emitter->on(Events::UNAPPLY, function (Data $data) use ($transformer) {
            $data->setInput($transformer->reverseTransform($data->getInput()));
        });

        return $this;
    }

    /**
     * Adds a constraint.
     *
     * @param Constraint $constraint The constaint object
     */
    public function validate(Constraint $cons)
    {
        $emitter = $this->emitter;
        $emitter->on(Events::APPLIED, function (Data $data) use ($cons) {
            if (count($data->getErrors()) > 0) {
                return;
            }

            if (false === $cons->check($data->getResult())) {
                $error = new Error($cons->getMessage(), $data->getPropertyPath());
                $data->addError($error);
            }
        });

        return $this;
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
        if (null !== $func) {
            $this->transform(new Transformer\Callback($func));
        }

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
     * @param mixed    $data The data to unapply
     * @param callable $func Callback function to transform the final data
     *
     * @return mixed
     */
    public function unapply($input, callable $func = null)
    {
        if (null !== $func) {
            $this->transform(new Transformer\Callback(null, $func));
        }

        $data = new Data($input);
        $this->emitter->emit(Events::UNAPPLY, $data, $this);
        $input = $data->getInput();

        if (!$this->hasChildren()) {
            return $input;
        }

        $result = [];

        foreach ($this->getChildren() as $name => $child) {
            if (is_array($input) && array_key_exists($name, $input)) {
                $result[$name] = $child->unapply($input[$name]);
            }
        }

        return $result;
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
