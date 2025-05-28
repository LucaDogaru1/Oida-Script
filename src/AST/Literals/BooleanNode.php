<?php

namespace Oida\AST\Literals;

use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class BooleanNode extends ASTNode
{

    private string $bool;

    public function __construct(string $bool)
    {
        $this->bool = $bool === 'basst';
    }

    public function evaluate(Environment $env): bool
    {
        return $this->bool;
    }
}