<?php

namespace Oida\AST\Expression;

use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class VoidExpressionNode extends ASTNode
{
    private ASTNode $expression;

    public function __construct(ASTNode $expression)
    {
        $this->expression = $expression;
    }

    public function evaluate(Environment $env): null
    {
        $this->expression->evaluate($env);
        return null;
    }

    public function __toString(): string
    {
        return '';
    }

    public function getType(): string
    {
        return 'void';
    }
}