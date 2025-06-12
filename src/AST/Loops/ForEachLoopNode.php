<?php

namespace Oida\AST\Loops;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\Environment\Environment;

class ForEachLoopNode extends ASTNode
{
    private ASTNode  $arrayName;
    private string $itemName;

    private ?string $keyName = null;

    private CodeBlockNode $body;


    public function __construct(ASTNode  $arrayName, string $itemName, CodeBlockNode $body, ?string $keyName = null)
    {
        $this->arrayName = $arrayName;
        $this->itemName = $itemName;
        $this->body = $body;
        $this->keyName = $keyName;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): void
    {
        $array = $this->arrayName->evaluate($env);
        $local = new Environment($env);


        foreach ($array as $key => $value) {
            if ($this->keyName !== null) {
                $local->defineVariable($this->keyName, $key);
            }
            $local->defineVariable($this->itemName, $value);
            foreach ($this->body->getStatements() as $stmt) {
                $stmt->evaluate($local);
            }
        }
    }
}

