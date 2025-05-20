<?php

namespace Oida\AST\Class;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\IdentifierNode;
use Oida\Environment\Environment;

class ClassVariableNode extends ASTNode
{
    private string $visibility;

    private IdentifierNode $name;
    private  $value;

    public function __construct(string $visibility, IdentifierNode $name,  $value)
    {
        $this->visibility = $visibility;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): void
    {
        $value = $this->value->evaluate($env);
        $object = $env->getCurrentObject();


        $object->setProperty(
            $this->name->getName(),
            $value,
            $this->visibility
        );
    }

    public function getName(): string
    {
        return $this->name->getName();
    }

    public function getValue(): ASTNode
    {
        return $this->value;
    }

    public function getVisibility(): string
    {
        return $this->visibility;
    }
    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function setVisibility(string $visibility): void
    {
        $this->visibility = $visibility;
    }

}