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

            while ($value instanceof ASTNode) {
                $value = $value->evaluate($env);
            }

            if ($value === null || $value instanceof VoidValue) continue;

            if (is_bool($value)) {
                $output .= $value ? 'basst' : 'sichaned';
                continue;
            }

            if (is_array($value)) {
                $output .= print_r($value, true);
                continue;
            }

            if (is_object($value)) {
                if (method_exists($value, '__toString')) {
                    $output .= (string)$value;
                } else {
                    $output .= print_r($value, true);
                }
                continue;
            }

            $output .= $value;
        }

        echo $output . "\n";
        return $output;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}