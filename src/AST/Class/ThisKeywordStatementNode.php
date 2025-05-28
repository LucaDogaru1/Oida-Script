<?php

namespace Oida\AST\Class;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Environment\ClassInstance;
use Oida\Environment\Environment;

class ThisKeywordStatementNode extends ASTNode
{
    private IdentifierNode $propertyName;
    private ?ASTNode $valueExpression;

    public function __construct(IdentifierNode $propertyName, ?ASTNode $valueExpression = null)
    {
        $this->type = 'classInstance';
        $this->propertyName = $propertyName;
        $this->valueExpression = $valueExpression;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        $currentObject = $env->getCurrentObject();

        if (!$currentObject instanceof ClassInstance) {
            throw new Exception("Das Object ist keine Klassen Instanc.");
        }

        $propertyName = $this->propertyName->getName();
        if ($this->valueExpression !== null) {

            $newValue = $this->valueExpression->evaluate($env);

            $visibility = 'public';

            if ($currentObject->hasProperty($propertyName)) {
                $visibility = $currentObject->getPropertyVisibility($propertyName);
            }

            $currentObject->setProperty($propertyName, $newValue, $visibility);

            return $newValue;
        }

        $property = $currentObject->getProperty($propertyName);
        return $property['value'];
    }
}