<?php

namespace Oida\AST;

use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class VariableNode extends ASTNode
{

    private IdentifierNode $name;
    private ASTNode $value;

    public function __construct(IdentifierNode $name, ASTNode $value)
    {
        $this->type = 'variable';
        $this->name = $name;
        $this->value = $value;
    }

    public function evaluate(Environment $env): void
    {
        $name = $this->name->getName();
        $value = $this->value->evaluate($env);
        $env->defineVariable($name, $value);
    }

    public function getName(): string
    {
        return $this->name->getName();
    }

    public function getValue(): ASTNode
    {
        return $this->value;
    }
}