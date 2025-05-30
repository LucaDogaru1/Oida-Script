<?php

namespace Oida\Parser\ConditionExpression;

use Exception;
use Oida\AST\ConditionOperationNode;
use Oida\AST\VoidValue;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;

class ParseConditionExpression extends BaseParser
{
    private array $operators = [
        'und' => ['prio' => 1, 'assoc' => 'left'],
        'oda' => ['prio' => 1, 'assoc' => 'left'],
        'gleich' => ['prio' => 2, 'assoc' => 'left'],
        'isned' => ['prio' => 2, 'assoc' => 'left'],
        'größer' => ['prio' => 2, 'assoc' => 'left'],
        'größerglei' => ['prio' => 2, 'assoc' => 'left'],
        'klana' => ['prio' => 2, 'assoc' => 'left'],
        'klanaglei' => ['prio' => 2, 'assoc' => 'left'],
        '!' => ['prio' => 3, 'assoc' => 'right'],
    ];

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex, int $minPrecedence = 0): ?array
    {
        $this->currentIndex = $tokenIndex;

        $negationExpr = $this->checkForNegation();
        if ($negationExpr !== null)  return $negationExpr;

        $leftExpr = (new ParseExpression($this->tokens))->parse($this->currentIndex);
        if (!$leftExpr) return null;
        [$leftNode, $this->currentIndex] = $leftExpr;

        while ($this->match('T_COMPARISON_OPERATOR', false))
        {
            $operator = $this->tokens[$this->currentIndex][1];
            $info = $this->getOperatorInfo($operator);
            $prio = $info['prio'];
            $assoc = $info['assoc'];

            if ($prio < $minPrecedence) break;

            $this->match('T_COMPARISON_OPERATOR');
            $nextMin = $assoc === 'left' ? $prio + 1 : $prio;

            $rightExpr = $this->parse($this->currentIndex, $nextMin);
            if (!$rightExpr) throw new Exception("Fehlender Ausdruck nach '{$operator}'");

            [$rightNode, $this->currentIndex] = $rightExpr;

            $leftNode = new ConditionOperationNode($leftNode, $operator, $rightNode);
        }

        return [$leftNode, $this->currentIndex];
    }

    /**
     * @throws Exception
     */
    private function checkForNegation(): ?array
    {
        $token = $this->tokens[$this->currentIndex];

        if ($token[0] === 'T_COMPARISON_OPERATOR' && $token[1] === '!') {
            $this->currentIndex++;
            $expr = $this->parse($this->currentIndex, 3);
            if (!$expr) throw new Exception("🛑 Nach dem '!' fehlt ein Ausdruck");
            [$node, $this->currentIndex] = $expr;
            return [new ConditionOperationNode($node, '!', new VoidValue()), $this->currentIndex];
        }
        return null;
    }

    /**
     * @throws Exception
     */
    private function getOperatorInfo(string $op): array
    {
        return $this->operators[$op] ?? throw new Exception("Unbekannter Operator: '{$op}'");
    }
}