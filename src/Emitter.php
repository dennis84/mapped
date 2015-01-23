<?php

namespace Mapped;

/**
 * Emitter.
 */
class Emitter
{
    protected $callbacks = [];

    /**
     * Adds an event listener.
     *
     * @param string   $event The event name
     * @param callable $fn    The callback
     */
    public function on($event, callable $fn)
    {
        $this->callbacks[$event][] = $fn;
    }

    /**
     * Emit.
     *
     * @param string $event
     */
    public function emit($event)
    {
        $args = array_slice(func_get_args(), 1);
        if (!array_key_exists($event, $this->callbacks)) {
            return;
        }

        foreach ($this->callbacks[$event] as $fn) {
            call_user_func_array($fn, $args);
        }
    }
}
