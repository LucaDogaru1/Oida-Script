<?php

namespace Oida\AST\Function;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\VoidValue;
use Oida\Environment\Environment;
use Oida\Exceptions\ReturnException;

class FunctionCallNode extends ASTNode
{
    private string $functionName;
    private ?array $args;

    public function __construct(string $functionName, ?array $args = null)
    {
        $this->functionName = $functionName;
        $this->args = $args ?? [];
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        $function = $env->getFunction($this->functionName);
        $localEnv = new Environment($env);

        $this->checkForArgs($function, $env, $localEnv);

        try {
            foreach ($function['body']->getStatements() as $body) {
                $body->evaluate($localEnv);
            }
            return new VoidValue();
        } catch (ReturnException $e) {
            return $e->getValue();
        }

    }


    /**
     * @throws Exception
     */
    private function checkForArgs($function, $env, $localEnv): void
    {
        $functionParams = $function['parameters'];

        if (count($functionParams) !== count($this->args)) {
            throw new Exception("🛑 Musst halt schon die richtige Anzahl an Argumente weitergeben bei '{$this->functionName}'");
        }

        foreach ($functionParams as $i => $param) {
            $localEnv->defineVariable($param->getName(), $this->args[$i]->evaluate($env));
        }
    }
}