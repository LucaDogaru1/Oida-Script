<?php

namespace Oida\AST\Class;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\IdentifierNode;
use Oida\Environment\Environment;

class MethodCallNode extends ASTNode
{

    private ASTNode $object;
    private IdentifierNode $methodName;
    private ?array $args;


    public function __construct(ASTNode $object, IdentifierNode $methodName, ?array $args = null)
    {
        $this->object = $object;
        $this->methodName = $methodName;
        $this->args = $args ?? [];
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {


        $objectInstance = $this->object->evaluate($env);

        $method = $objectInstance->getMethod($this->methodName->getName());


        $methodParameters = $method->getArgs();
        $methodBody = $method->getBody();

        $localEnv = new Environment($env);
        $localEnv->setCurrentObject($objectInstance);
        $localEnv->setInsideClass($objectInstance->getClassName());

        if (
            $method->getVisibility() === 'privat' &&
            (
                !$env->insideClass() ||
                $env->getInsideClassName() !== $objectInstance->getClassName()
            )
        ) {
            throw new Exception("🛑 \033[1;31mOida, was glaubst eigentlich?\033[0m\n" .
                "\033[1;97m'{$this->methodName->getName()}'\033[0m is ne \033[1;97mprivate methode\033[0m in \033[1;97m'{$objectInstance->getClassName()}'\033[0m,\n" .
                "\033[1;31mwas versuchst da mit der Methode, schau da halt nochmal die basics von OOP an, oder ruf die da ned auf.\033[0m\n");
        }

        if (count($methodParameters) > 0) {
            foreach ($methodParameters as $index => $paramName) {
                if (isset($this->args[$index])) {
                    $localEnv->defineVariable($paramName->getName(), $this->args[$index]->evaluate($env));
                }
            }
        }

        $result = null;
        foreach ($methodBody->getStatements() as $node) {
            $result = $node->evaluate($localEnv);
        }

        return $result;
    }

    public function getName(): string
    {
        return $this->methodName->getName();
    }
}