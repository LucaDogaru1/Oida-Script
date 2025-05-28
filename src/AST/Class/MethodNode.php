<?php

namespace Oida\AST\Class;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Environment\Environment;

class MethodNode extends ASTNode
{

    private string $visibility;
    private IdentifierNode $methodName;
    private ?array $args;
    private CodeBlockNode $body;



    public function __construct(string $visibility, IdentifierNode $methodName, CodeBlockNode $body, ?array $args = null)
    {
        $this->type = 'method';
        $this->visibility = $visibility;
        $this->methodName = $methodName;
        $this->body = $body;
        $this->args = $args;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): void
    {
        $env->defineMethod($this->methodName->getName(), $this->body, $this->args, $this->visibility);
    }

    public function getArgs(): ?array
    {
        return $this->args;
    }


    public function getBody(): CodeBlockNode
    {
        return $this->body;
    }

    public function getMethodName(): string
    {
        return $this->methodName->getName();
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

}