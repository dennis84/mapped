<?php

namespace Mapped;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

/**
 * Event.
 *
 * @author Dennis Dietrich <d.dietrich84@gmail.com>
 */
class Event extends BaseEvent
{
    protected $mapping;
    protected $result;
    protected $data;

    /**
     * Constructor.
     *
     * @param Mapping $mapping The mapping object
     * @param mixed   $result  The mapping result
     * @param mixed   $data    The applied or unapplied data
     */
    public function __construct(Mapping $mapping, $result = null, $data = null)
    {
        $this->mapping = $mapping;
        $this->result = $result;
        $this->data = $data;
    }

    /**
     * Gets the mapping object.
     *
     * @return Mapping
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * Sets the result.
     *
     * @param mixed $result The result data
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * Gets the result data.
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Gets the data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the data.
     *
     * @param mixed $data The data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
