<?php

namespace Oida\AST\Loops;

use Oida\AST\ASTNode;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\AST\ConditionOperationNode;
use Oida\AST\VoidValue;
use Oida\Environment\Environment;
use Oida\Exceptions\ReturnException;

class WhileLoopNode extends ASTNode
{

    private ConditionOperationNode $conditionOperationNode;
    private CodeBlockNode $body;

    public function __construct(ConditionOperationNode $conditionOperationNode, CodeBlockNode $body)
    {
        $this->conditionOperationNode = $conditionOperationNode;
        $this->body = $body;
    }

    /**
     * @throws \Exception
     */
    public function evaluate(Environment $env): VoidValue
    {
            while($this->conditionOperationNode->evaluate($env)){
                foreach ($this->body->getStatements() as $body) {
                    $body->evaluate($env);
                }
            }
            return new VoidValue();
        }
}