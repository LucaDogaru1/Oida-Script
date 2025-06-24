<?php

namespace Oida\AST\Test;

use Exception;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;

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

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): null
    {

        if (!$env->isInTestContext()) {
            throw new \Exception("\e[97m[Fehler] \e[31mschau darf nur innerhalb eines Tests verwendet werden, verstehst?\e[0m");
        }

        $leftValue = $this->left->evaluate($env);
        $rightValue = $this->right->evaluate($env);

        TestNode::addAssertion();

        switch ($this->operator) {
            case 'gleich':
                assertEquals($rightValue, $leftValue);
                break;
            case 'isned':
                assertNotEquals($rightValue, $leftValue);
                break;
            default:
                throw new \Exception("Operator {$this->operator} wird noch nicht unterstützt.");
        }

        return null;
    }
}