<?php

namespace Oida\AST\Class;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Environment\ClassInstance;
use Oida\Environment\Environment;
use Oida\Exceptions\ReturnException;

class ThisKeywordNode extends ASTNode
{


    private ?IdentifierNode $propertyName;

    public function __construct(?IdentifierNode $propertyName = null)
    {
        $this->propertyName = $propertyName;
    }


    /**
     * @throws Exception
     */

    public function evaluate(Environment $env)
    {
        $obj = $env->getCurrentObject();

        if ($this->propertyName === null) {
            return $obj;
        }

        return $obj->getProperty($this->propertyName->getName());
    }

    public function getPropertyName(): ?string
    {
        return $this->propertyName->getName();
    }
}