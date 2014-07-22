<?php

namespace Mapped;

/**
 * Events.
 */
class Events
{
    /**
     * This event will be fired at the beginning of the `apply` process. It
     * allows you to modify the incoming data.
     */
    const APPLY = 'mapped.apply';

    /**
     * This event will be fired at the beginning of the `unapply` process. It
     * allows you to modify the incoming data.
     */
    const UNAPPLY = 'mapped.unapply';
}
