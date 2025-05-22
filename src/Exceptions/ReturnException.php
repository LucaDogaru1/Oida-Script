<?php

namespace Oida\Exceptions;

class ReturnException extends \Exception
{

    private mixed $value;

    public function __construct($value)
    {
        $this->value = $value;
        parent::__construct("Function returned");
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

}