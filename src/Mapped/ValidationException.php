<?php

namespace Mapped;

class ValidationException extends \Exception
{
    public function __construct(Mapping $mapping, $message)
    {
        parent::__construct($message);
    }
}
