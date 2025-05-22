<?php

namespace Oida\AST;

use Exception;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\Environment\Environment;

class FunctionNode extends ASTNode
{
    private IdentifierNode $name;
    private array $args ;
    private ASTNode $body;

    public function __construct(IdentifierNode $name, CodeBlockNode $body, ?array $args = [])
    {
        $this->type = 'function';
        $this->name = $name;
        $this->body = $body;
        $this->args = $args;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): void
    {
        $env->defineFunction($this->name->getName(), $this->body, $this->args);
    }


    public function getBody(): CodeBlockNode|ASTNode
    {
        return $this->body;
    }

    public function getArgs(): ?array
    {
        return $this->args;
    }
}