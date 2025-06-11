<?php

namespace Oida\Exceptions;

class FehlerException extends \Exception
{

    private mixed $value;

    public function __construct($value)
    {
        $this->value = $value;
        parent::__construct((string)$value);
    }

}