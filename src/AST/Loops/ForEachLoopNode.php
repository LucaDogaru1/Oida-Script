<?php

namespace Oida\AST\Loops;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\AST\IdentifierNode;
use Oida\Environment\Environment;

class ForEachLoopNode extends ASTNode
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
    public function evaluate(Environment $env): void
    {
        $array = $env->getVariable($this->arrayName);

        if(!is_array($array)) {
            throw new Exception("🛑 '{$this->arrayName}' ist halt kein Array – geht ned mit foreach.");
        }

        foreach ($array as $item) {
            $local = new Environment($env);
            $local->defineVariable($this->itemName, $item);
            foreach ($this->body->getStatements() as $stmt) {
                $stmt->evaluate($local);
            }
        }
    }
}