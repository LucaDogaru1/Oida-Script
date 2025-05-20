<?php

namespace Oida\AST\CodeBlock;

use Oida\AST\ASTNode;

use Oida\Environment\Environment;

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

    public function evaluate(Environment $env)
    {
        $result = null;
        foreach ($this->statements as $statement) {
             $result = $statement->evaluate($env);
        }
        return $result;
    }
}