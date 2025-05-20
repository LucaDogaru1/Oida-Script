<?php

namespace Oida\AST;

use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class FunctionNode extends ASTNode
{
    private IdentifierNode $name;
    private array $args ;
    private array $body;

    public function __construct(IdentifierNode $name, array $body, ?array $args = [])
    {
        $this->type = 'function';
        $this->name = $name;
        $this->body = $body;
        $this->args = $args;
    }

    public function evaluate(Environment $env)
    {
        // TODO: Implement evaluate() method.
    }
}