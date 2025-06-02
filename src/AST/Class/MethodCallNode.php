<?php

namespace Oida\AST\Class;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\AST\VoidValue;
use Oida\Environment\Environment;
use Oida\Exceptions\ReturnException;

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

        $this->checkAccess($env, $objectInstance, $method);
        $localEnv = $this->prepareMethodEnvironment($env, $objectInstance, $method);

        try {
            foreach ($method->getBody()->getStatements() as $stm) {
                $stm->evaluate($localEnv);
            }
            return new VoidValue();
        } catch (ReturnException $e) {
            return $e->getValue();
        }
    }

    /**
     * @throws Exception
     */
    private function checkAccess(Environment $env, $objectInstance, $method): void
    {
        if (
            $method->getVisibility() === 'privat' &&
            (
                !$env->insideClass() ||
                $env->getInsideClassName() !== $objectInstance->getClassName()
            )
        ) {
            throw new Exception(
                "🛑 \033[1;31mOida, was glaubst eigentlich?\033[0m\n" .
                "\033[1;97m'{$this->methodName->getName()}'\033[0m is ne \033[1;97mprivate methode\033[0m in \033[1;97m'{$objectInstance->getClassName()}'\033[0m,\n" .
                "\033[1;31mwas versuchst da mit der Methode, schau da halt nochmal die basics von OOP an, oder ruf die da ned auf.\033[0m\n"
            );
        }
    }

    private function prepareMethodEnvironment(Environment $parentEnv, $objectInstance, $method): Environment
    {
        $localEnv = new Environment($parentEnv);
        $localEnv->setCurrentObject($objectInstance);
        $localEnv->setInsideClass($objectInstance->getClassName());

        $args = $this->args ?? [];
        foreach ($method->getArgs() as $i => $param) {
            if (isset($args[$i])) {
                $localEnv->defineVariable($param->getName(), $args[$i]->evaluate($parentEnv));
            }
        }

        return $localEnv;
    }

    public function getName(): string
    {
        return $this->methodName->getName();
    }

    public function toPHP(): string
    {
        $argsPhp = array_map(fn($arg) => $arg->toPHP(), $this->args);
        return $this->object->toPHP() . '->' . $this->methodName->getName() . '(' . implode(', ', $argsPhp) . ');';
    }
}