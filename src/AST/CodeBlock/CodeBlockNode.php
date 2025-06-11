<?php

namespace Oida\AST\CodeBlock;

use Oida\AST\ASTNode;

use Oida\AST\VoidValue;
use Oida\Environment\Environment;
use Oida\Exceptions\ReturnException;

class CodeBlockNode extends ASTNode
{
    private array $statements;

    public function __construct(array $statements)
    {
        $this->statements = $statements;
    }

    public function getStatements(): array
    {
        return $this->statements;
    }

    public function evaluate(Environment $env): VoidValue
    {
        foreach ($this->statements as $statement) {
            try {
                $statement->evaluate($env);
            } catch (ReturnException $e) {
                throw $e;
            }
        }
        return new VoidValue();
    }
}