<?php

namespace Oida\AST;

use Oida\Environment\Environment;

abstract class ASTNode
{
    protected string $type;


    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
    abstract public function evaluate(Environment $env);
}