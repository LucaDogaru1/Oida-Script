<?php

namespace Oida\AST\Class;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Environment\ClassInstance;
use Oida\Environment\Environment;

class ObjectNode extends ASTNode
{

    private IdentifierNode $objectName;
    private ?array $constructorArguments = [];

    public function __construct(IdentifierNode $objectName, array $constructorArguments = [])
    {
        $this->setType('classInstance');
        $this->objectName = $objectName;
        $this->constructorArguments = $constructorArguments ?? [];
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): ClassInstance
    {
        $className = $this->objectName->getName();

        $classDef = $env->getClass($className);

        if (!$classDef) {
            throw new Exception("Gibt es eine Klasse mit dem Namen: $className ?");
        }

        $instance = new ClassInstance( $this->objectName->getName(), $classDef['methods'], $classDef['properties']);

        if (!$classDef['constructor']) {
            foreach ($classDef['properties'] as $property) {
                $value = $property->getValue();
                $instance->setProperty($property->getName(), $value, $property->getVisibility());
            }
        }

        $env->setCurrentObject($instance);

        if ($classDef['constructor']) {
            $constructorNode = $classDef['constructor'];
            $evaluatedArgs = [];
            foreach ($this->constructorArguments as $argNode) {
                $evaluatedArgs[] = $argNode->evaluate($env);  // ⬅️ hier findet z. B. lookup von "scoda" statt
            }
            $constructorNode->setEvaluatedArgs($evaluatedArgs);;

            return $constructorNode->evaluate($env);
        }

        return $instance;
    }

    /**
     * @throws Exception
     */


    public function getObjectName(): string
    {
        return $this->objectName->getName();
    }

    public function getConstructorArgs(): array
    {
        return $this->constructorArguments;
    }

    /**
     * @throws Exception
     */
    public function getMethods(Environment $env): array
    {
        $className = $this->objectName->getName();
        $classDef = $env->getClass($className);

        if (!$classDef) {
            throw new Exception("Gibt keine Klasse mit dem Namen '$className'.");
        }

        return $classDef['methods'] ?? [];
    }

    /**
     * @throws Exception
     */
    public function getProperties(Environment $env): array
    {
        $className = $this->objectName->getName();
        $classDef = $env->getClass($className);

        if (!$classDef) {
            throw new Exception("Gibt keine Klasse mit dem Namen '$className'.");
        }

        return $classDef['properties'] ?? [];
    }


}