<?php

namespace Oida\AST\Class;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\IdentifierNode;
use Oida\Environment\ClassInstance;
use Oida\Environment\Environment;

class ThisKeywordNode extends ASTNode
{


    private IdentifierNode $propertyName;
    private ?array $args = null;

    public function __construct(IdentifierNode $propertyName, ?array $args)
    {
        $this->propertyName = $propertyName;
        $this->args = $args ?? null;
    }


    /**
     * @throws Exception
     */

    public function evaluate(Environment $env)
    {
        $currentObject = $env->getCurrentObject();

        if (!$currentObject instanceof ClassInstance) {
            throw new Exception("Das Object ist keine Klassen Instanz.");
        }

        $name = $this->propertyName->getName();

        return $this->args !== null
            ? $this->evaluateMethod($currentObject, $env, $name)
            : $this->evaluateProperty($currentObject, $env, $name);
    }

    /**
     * @throws Exception
     */
    private function evaluateMethod(ClassInstance $object, Environment $env, string $name)
    {
        $method = $object->getMethod($name);

        if (
            $method->getVisibility() === 'privat' &&
            (
                !$env->insideClass() ||
                $env->getInsideClassName() !== $object->getClassName()
            )
        ) {
            throw new Exception("🛑 \033[1;31mOida, was glaubst eigentlich?\033[0m\n" .
                "\033[1;97m'{$method->getMethodName()}'\033[0m is ne \033[1;97mprivate methode\033[0m in \033[1;97m'{$objectInstance->getClassName()}'\033[0m,\n" .
                "\033[1;31mwas versuchst da mit der Methode, schau da halt nochmal die basics von OOP an, oder ruf die da ned auf.\033[0m\n");
        }

        $localEnv = new Environment($env);
        $localEnv->setCurrentObject($object);
        $localEnv->setInsideClass($object->getClassName());

        foreach ($method->getArgs() as $index => $param) {
            if (isset($this->args[$index])) {
                $localEnv->defineVariable($param->getName(), $this->args[$index]->evaluate($env));
            }
        }

        $result = null;
        foreach ($method->getBody()->getStatements() as $node) {
            $result = $node->evaluate($localEnv);
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private function evaluateProperty(ClassInstance $object, Environment $env, string $name)
    {
        $property = $object->getProperty($name);

        return $property['value'] instanceof ASTNode
            ? $property['value']->evaluate($env)
            : $property['value'];
    }


}