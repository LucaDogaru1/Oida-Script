<?php

namespace Oida\AST\Class;

use AllowDynamicProperties;
use Exception;
use Oida\AST\ASTNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Environment\Environment;

#[AllowDynamicProperties] class ClassNode extends ASTNode
{

    protected IdentifierNode $className;
    protected ?array $methods;
    protected ?array $properties;

    protected ?ConstructorNode $constructorNode;


    public function __construct(IdentifierNode $className, ?array $methods = null, ?array $properties = null, ?ConstructorNode $constructor = null) {
        $this->type = 'class';
        $this->className = $className;
        $this->properties = $properties ?? [];
        $this->methods = $methods ?? [];
        $this->constructorNode = $constructor ?? null;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): null
    {

        $env->defineClass(
            $this->getClassName(),
            $this->getMethods(),
            $this->getProperties(),
            $this->getConstructor()
        );

        return null;
    }


    public function getClassName(): string
    {
        return $this->className->getName();
    }
    /**
     * @return array|null
     */
    public function getProperties() :?array
    {
        return $this->properties;
    }


    /**
     * @return array|null
     */
    public function getMethods(): ?array
    {
        return $this->methods;
    }

    /**
     * @return array|null
     */
    public function getConstructor(): ?ConstructorNode
    {
        return $this->constructorNode;
    }


}