<?php

namespace Oida\AST\HigherOrderFunction;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\AST\ConditionOperationNode;
use Oida\Environment\Environment;

class MapNode extends ASTNode
{

    private string $arrayName;
    private string $itemName;
    private CodeBlockNode $body;

    public function __construct(string $arrayName, string $itemName, CodeBlockNode $body)
    {
        $this->arrayName = $arrayName;
        $this->itemName = $itemName;
        $this->body = $body;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): array
    {
        $variable = $env->getVariable($this->arrayName);
        $result = [];

        foreach($variable as $item) {
            $local = new Environment($env);
            $local->defineVariable($this->itemName, $item);
            foreach ($this->body->getStatements() as $body){
                $result[] = $body->evaluate($local);
            }
        }
        return $result;
    }
}