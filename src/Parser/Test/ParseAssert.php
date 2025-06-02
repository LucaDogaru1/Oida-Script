<?php

namespace Oida\Parser\Test;

use Oida\AST\Test\AssertNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;

class ParseAssert extends BaseParser
{

    /**
     * @throws \Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;
        if (!$this->match('T_ASSERT')) return null;

        $this->expect('T_OPENING_PARENTHESIS');

        $exprParser = new ParseExpression($this->tokens);
        [$left, $this->currentIndex] = $exprParser->parse($this->currentIndex);

        $this->expect('T_COMPARISON_OPERATOR');
        $operatorToken = $this->tokens[$this->currentIndex -1][1];

        [$right, $this->currentIndex] = $exprParser->parse($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');

        return [new AssertNode($left, $operatorToken, $right), $this->currentIndex];
    }

}