<?php

namespace Oida\AST\DataStructure;

use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class ArrayNode extends ASTNode
{
    private array $values;


    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function evaluate(Environment $env): array
    {
        $evaluated = [];
       foreach ($this->values as $value) {
           $evaluated[] = $value->evaluate($env);
       }

       return $evaluated;
    }

}