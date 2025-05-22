<?php

namespace Oida\AST\Loops;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\BinaryOperationNode;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\AST\ConditionOperationNode;
use Oida\AST\VariableNode;
use Oida\AST\VoidValue;
use Oida\Environment\Environment;
use Oida\Exceptions\ReturnException;

class ForLoopNode extends ASTNode
{
    private VariableNode $variable;
    private ConditionOperationNode $conditionOperationNode;

    private BinaryOperationNode $binaryOperationNode;
    private CodeBlockNode $body;


    public function __construct(VariableNode $variable, ConditionOperationNode $conditionOperationNode, BinaryOperationNode $binaryOperationNode, CodeBlockNode $body)
    {
        $this->variable = $variable;
        $this->conditionOperationNode = $conditionOperationNode;
        $this->binaryOperationNode = $binaryOperationNode;
        $this->body = $body;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        $local = new Environment($env);

        $name = $this->variable->getName();
        $value = $this->variable->getValue()->evaluate($local);
        $local->defineVariable($name, $value);

        try {
            while ($this->conditionOperationNode->evaluate($local)) {
                foreach ($this->body->getStatements() as $body) {
                    $body->evaluate($local);
                }
                $this->binaryOperationNode->evaluate($local);
            }
            return new VoidValue();
        } catch (ReturnException $e) {
            return $e->getValue();
        }

    }
}