<?php

namespace Oida\AST\Loops;

use Exception;
use Oida\AST\Access\PropertyAccessNode;
use Oida\AST\ASTNode;
use Oida\AST\BinaryOperationNode;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\AST\ConditionOperationNode;
use Oida\AST\VariableNode;
use Oida\AST\VoidValue;
use Oida\Environment\Environment;
use Oida\Exceptions\ReturnException;
use function PHPUnit\Framework\isEmpty;

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

        $this->checkForArrayLoop($local);

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


    /**
     * @throws Exception
     */
    private function checkForArrayLoop($local): void
    {
        $rightNode = $this->conditionOperationNode->getRight();

        if($rightNode instanceof PropertyAccessNode) {
            $arrayName = $rightNode->getArrayName();
            $array = $local->getVariable($arrayName);
            if (empty($array)) {
                throw new Exception("🛑 \033[1;31m'wenn du über ein Array loopen willst, darf es halt nicht leer sein?'\033[0m");
            }
        }

    }
}