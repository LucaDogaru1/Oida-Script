<?php

namespace Oida\AST;

use Exception;
use Oida\Environment\Environment;

class ConditionOperationNode extends ASTNode
{  public function __construct(
    private ASTNode $left,
    private string $operator,
    private ?ASTNode $right = null
){}

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): bool
    {
        $l = $this->left->evaluate($env);
        $r = $this->right->evaluate($env);

        return match ($this->operator) {
            '!' => !$l,
            'und' => $l && $r,
            'oda' => $l || $r,
            'gleich' => $l == $r,
            'isned' => $l !== $r,
            'größer' => $l > $r,
            'größerglei' => $l >= $r,
            'klana' => $l < $r,
            'klanaglei' => $l <= $r,
            default => throw new Exception("Unbekannter logischer Operator '{$this->operator}'")
        };
    }


    public function getRight(): ASTNode
    {
        return  $this->right;
    }
}