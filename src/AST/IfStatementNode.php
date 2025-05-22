<?php

namespace Oida\AST;

use Oida\AST\ASTNode;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\Environment\Environment;
use Oida\Exceptions\ReturnException;

class IfStatementNode extends ASTNode
{

    private ASTNode  $condition;

    private CodeBlockNode $body;

    private ?array $elseIfBlock;
    private ?CodeBlockNode $elseBody;

    public function __construct(ASTNode $condition, CodeBlockNode $body, ?array $elseIfBlock = null, ?CodeBlockNode $elseBody = null)
    {
        $this->condition = $condition;
        $this->body = $body;
        $this->elseIfBlock = $elseIfBlock;
        $this->elseBody = $elseBody;
    }

    public function evaluate(Environment $env)
    {
        if ($this->condition->evaluate($env)) {
            return $this->body->evaluate($env);
        }

        if ($this->elseIfBlock) {
            foreach ($this->elseIfBlock as $block) {
                $condition = $block['condition'];
                $body = $block['body'];
                if ($condition->evaluate($env)) {
                    return $body->evaluate($env);
                }
            }
        }

        if ($this->elseBody) {
            return $this->elseBody->evaluate($env);
        }

        return new VoidValue();
    }
}