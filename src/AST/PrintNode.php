<?php

namespace Oida\AST;

use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class PrintNode extends ASTNode
{

    private array $values;
    public function __construct(array $values )
    {
        $this->type = 'print';
        $this->values = $values;
    }

    public function evaluate(Environment $env)
    {
        $output = '';
        foreach ($this->values as $valueNode) {
            $output.= $valueNode->evaluate($env);
        }
        echo $output;
        return $output;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}