<?php

namespace Oida\AST\Literals;

use AllowDynamicProperties;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;

#[AllowDynamicProperties] class NumberNode extends ASTNode
{
    private int $value;

    public function __construct(int $value) {
        $this->type = 'number';
        $this->value = $value;
    }


    public function evaluate(Environment $env): int
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    public function toPHP(): string {
        return (string)$this->value;
    }
}