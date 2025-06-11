<?php

namespace Oida\AST\Expression;

use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class ExpressionStatementNode extends ASTNode
{
    private ASTNode $expression;

    public function __construct(ASTNode $expression)
    {
        $this->expression = $expression;
    }

    public function evaluate(Environment $env)
    {
        return $this->expression->evaluate($env);
    }
}