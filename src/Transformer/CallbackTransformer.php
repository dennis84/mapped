<?php

namespace Mapped\Transformer;

use Mapped\Transformer;

/**
 * CallbackTransformer.
 */
class CallbackTransformer extends Transformer
{
    private $transform;
    private $reverseTransform;
    private $expand = true;

    /**
     * Constructor.
     *
     * @param callable $transform        The tranform callback
     * @param callable $reverseTransform The reverse tranform callback
     * @param bool     $expand           Expands the arguments if true
     */
    public function __construct(callable $transform = null, callable $reverseTransform = null, $expand = true)
    {
        $this->transform = $transform;
        $this->reverseTransform = $reverseTransform;
        $this->expand = $expand;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        return $this->doTransform($data, $this->transform);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($data)
    {
        return $this->doTransform($data, $this->reverseTransform);
    }

    /**
     * Executes the given transform function.
     *
     * @param mixed    $data The data to tranform
     * @param callable $func The callback function
     *
     * @return mixed
     */
    private function doTransform($data, callable $func = null)
    {
        if (null === $func || null === $data) {
            return $data;
        }

        if (false === $this->expand) {
            return call_user_func($func, $data);
        }

        if (!is_array($data)) {
            $data = [$data];
        }

        return call_user_func_array($func, $data);
    }
}
