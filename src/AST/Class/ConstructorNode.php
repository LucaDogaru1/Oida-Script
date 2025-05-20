<?php

namespace Oida\AST\Class;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\Environment\ClassInstance;
use Oida\Environment\Environment;
use RuntimeException;

class ConstructorNode extends ASTNode
{
    private string $className;
    private array $args;

    private ?array $evaluatedArg = [];

    private CodeBlockNode $body;

    public function __construct(string $className, array $args, CodeBlockNode $body)
    {
        $this->className = $className;
        $this->args = $args;
        $this->body = $body;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): ClassInstance
    {
        $classDef = $env->getClass($this->className);

        $instance = new ClassInstance($this->className, $classDef['methods'], $classDef['properties']);
        $this->initializeProperties($instance, $classDef);
        $this->bindMethodsToInstance($instance, $env);
        $env->setCurrentObject($instance);

        $env->enterConstructor();
        try{
            foreach ($this->body->getStatements() as $statement) {
                $statement->evaluate($env);
            }
        } finally {
            $env->leaveConstructor();
        }

        return $instance;
    }

    /**
     * @throws Exception
     */
    private function initializeProperties(ClassInstance $instance, $classDef): void
    {
        $classProperties = $classDef['properties'];

        foreach ($this->args as $index => $paramNode) {
            $paramName = $paramNode->getName();
            $argValue = $this->getEvaluatedArgs($index);

            $propertyInfo = null;

            foreach ($classProperties as $property) {
                if ($property->getName() === $paramName) {
                    $propertyInfo = $property;
                    break;
                }
            }

            if ($propertyInfo === null) {
                throw new Exception("🛑 \033[1;91mArgument \033[1;4;97m'{$paramName}'\033[0m\033[1;91m im Konstruktor muss den selben Namen haben wie eine Klassenvariable. Hab ich so gemacht, weils der standart ist.\033[0m");
            }

            $visibility = $propertyInfo->getVisibility();


            $instance->setProperty($paramName, $argValue, $visibility);
        }
    }

    private function bindMethodsToInstance(ClassInstance $instance, Environment $env): void
    {
        foreach ($instance->getMethods() as $method) {
            $env->defineMethod($method->getMethodName(), $method->getBody(), $method->getArgs(), $method->getVisibility());
        }
    }


    public function getName(): string
    {
        return $this->className;
    }

    public function getConstructorBody(): CodeBlockNode
    {
        return $this->body;
    }

    public function getConstructorArgs(): array
    {
        return $this->args;
    }

    public function setClassName(string $name): void
    {
        $this->className = $name;
    }

    public function setEvaluatedArgs(array $args): void
    {

        $this->evaluatedArg = $args;
    }

    public function getEvaluatedArgs(int $index)
    {
        if (!isset($this->evaluatedArg[$index])) {
            throw new RuntimeException("Kein Argument an Index $index übergeben.");
        }

        return $this->evaluatedArg[$index];
    }

}