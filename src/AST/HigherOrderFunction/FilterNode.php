<?php

namespace Oida\AST\HigherOrderFunction;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\ConditionOperationNode;
use Oida\Environment\Environment;
use Oida\Exceptions\ReturnException;

class FilterNode extends ASTNode
{

    private string $arrayName;
    private string $itemName;
    private ConditionOperationNode $condition;

    public function __construct(string $arrayName, string $itemName, ConditionOperationNode $condition)
    {
        $this->arrayName = $arrayName;
        $this->itemName = $itemName;
        $this->condition = $condition;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): array
    {
        $variable = $env->getVariable($this->arrayName);

        if(!is_array($variable)){
            throw new Exception("🛑 '{$this->arrayName}' ist halt kein Array – geht ned mit filter.");
        }

        $result = [];

        foreach ($variable as $item) {
            $local = new Environment($env);
            $local->defineVariable($this->itemName, $item);

            if ($this->condition->evaluate($local)) {
                $result[] = $item;
            }
        }

        return $result;
    }
}