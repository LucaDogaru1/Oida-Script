<?php

namespace Oida\AST\Mock;

use Exception;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class MockNode extends ASTNode
{
    private string $methodName;
    private ASTNode $value;

    public function __construct(string $methodName, ASTNode $value)
    {
        $this->methodName = $methodName;
        $this->value = $value;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        if (!$env->isInTestContext()) {
            throw new Exception("\e[97m[Fehler] \e[31mmock() darf nur innerhalb eines Tests verwendet werden, verstehst?\e[0m");
        }
        $evaluatedValue = $this->value->evaluate($env);
        $env->setMockedMethod($this->methodName, $evaluatedValue);

        return $env->getMockedMethod($this->methodName);
    }
}