<?php

namespace Mapped;

/**
 * The mapping result. It contains the applied data and a flat array of error
 * objects.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class MappingResult
{
    protected $data;
    protected $errors = [];

    /**
     * Constructor.
     *
     * @param mixed   $data   The applied data
     * @param Error[] $errors An array of error objects
     */
    public function __construct($data, array $errors = [])
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    /**
     * Gets the applied data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Gets the errors.
     *
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
