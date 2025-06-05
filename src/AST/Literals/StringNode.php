<?php

namespace Oida\AST\Literals;

use AllowDynamicProperties;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;

#[AllowDynamicProperties] class StringNode extends ASTNode
{

    protected string $value;

    public function __construct(string $value)
    {
        $this->type = 'string';
        $this->value = $value;
    }

    public function evaluate(Environment $env): string
    {
        return $this->value;
    }

     /**
      * @return string
      */
     public function getValue(): string
     {
         return $this->value;
     }

}