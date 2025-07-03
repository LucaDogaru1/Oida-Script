<?php

namespace Oida\AST;

use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class NullExpressionNode extends ASTNode
{

    public function evaluate(Environment $env): mixed
    {
        return null;
    }
}