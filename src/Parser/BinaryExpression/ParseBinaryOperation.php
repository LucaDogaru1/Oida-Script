<?php

namespace Oida\Parser\BinaryExpression;

use Exception;
use Oida\AST\BinaryOperationNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParsePrimaryExpression;

class ParseBinaryOperation extends BaseParser
{
    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex, $minPrio): ?array
    {
        $this->currentIndex = $tokenIndex;

        $leftExpr = (new ParsePrimaryExpression($this->tokens))->parse($this->currentIndex);
        if (!$leftExpr) return null;
        [$leftNode, $this->currentIndex] = $leftExpr;

        while ($this->match('T_ARITHMETIC_OPERATOR', false)) {
            $operator = $this->tokens[$this->currentIndex][1];

            $info = OperatorPrios::getInfo($operator);
            $prio = $info['prio'];
            $assoc = $info['assoc'];

            if ($prio < $minPrio) break;

            $this->match('T_ARITHMETIC_OPERATOR');

            $nextMinPrio = $assoc === 'left' ? $prio + 1 : $prio;

            if(in_array($operator, ['plusplus', 'minusminus'])) return [new BinaryOperationNode($leftNode, $operator, null), $this->currentIndex];

            $rightExpr = (new ParseBinaryOperation($this->tokens))->parse($this->currentIndex, $nextMinPrio);
            if (!$rightExpr) {
                throw new Exception("Fehlender rechter Operand für Operator '{$operator}'");
            }

            [$rightNode, $this->currentIndex] = $rightExpr;
            $leftNode = new BinaryOperationNode($leftNode, $operator, $rightNode);
        }

        return [$leftNode, $this->currentIndex];
    }


}