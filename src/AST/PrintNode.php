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

    public function evaluate(Environment $env): string
    {
        $output = '';

        foreach ($this->values as $valueNode) {
            $value = $valueNode->evaluate($env);

            if ($value === null) continue;
            if ($value instanceof VoidValue) continue;
            if (is_bool($value)) echo $value ? 'basst' : 'sichaned';
            if (is_object($value) && !method_exists($value, '__toString')) continue;
            $output .= $value;
        }

        if(is_array($value) || is_object($value)) print_r($value);
        echo $output;
        return $output;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}