<?php

namespace Oida\AST\Test;

use Exception;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class AssertNode extends ASTNode
{
    private ASTNode $left;
    private string $operator;
    private ASTNode $right;

    public function __construct(ASTNode $left, string $operator, ASTNode $right) {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }

    public function evaluate(Environment $env): null
    {

        return null;
    }

    /**
     * @throws Exception
     */
    public function toPHP(): string {
        return match ($this->operator) {
            'gleich' => "\$this->assertEquals(" . $this->right->toPHP() . ", " . $this->left->toPHP() . ");",
            'isned'  => "\$this->assertNotEquals(" . $this->right->toPHP() . ", " . $this->left->toPHP() . ");",
            default  => throw new \Exception("Operator {$this->operator} wird noch nicht unterstützt."),
        };
    }
}