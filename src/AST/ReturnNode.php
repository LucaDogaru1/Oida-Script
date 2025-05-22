<?php

namespace Oida\AST;

use Oida\Environment\Environment;
use Oida\Exceptions\ReturnException;

class ReturnNode extends ASTNode
{
    private ASTNode $expression;

    public function __construct(ASTNode $expression) {
        $this->expression = $expression;
    }

    /**
     * @throws ReturnException
     */
    public function evaluate(Environment $env)
    {
        $value = $this->expression->evaluate($env);
        throw new ReturnException($value);
    }
}