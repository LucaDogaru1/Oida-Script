<?php

namespace Oida\AST\DataStructure;

use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class AssoArrayNode extends ASTNode
{
    private array $entries;

    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    public function evaluate(Environment $env): array
    {
        $result = [];

        foreach ($this->entries as [$keyNode, $valueNode]) {
            $evaluatedKey = $keyNode->evaluate($env);
            $evaluateValue = $valueNode->evaluate($env);
            $result[$evaluatedKey] = $evaluateValue;
        }
        return $result;
    }
}