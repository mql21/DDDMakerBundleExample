<?php

namespace Mql21\DDDMakerBundle\Exception;

use Throwable;

class ElementAlreadyExistsException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}