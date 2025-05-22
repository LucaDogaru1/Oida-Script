<?php

namespace Oida\Parser\Loops;

use Oida\AST\Loops\WhileLoopNode;
use Oida\Parser\BaseParser;
use Oida\Parser\ConditionExpression\ParseConditionExpression;
use Oida\Parser\ParseCodeBlock;

class ParseWhileLoop extends BaseParser
{

    /**
     * @throws \Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_WHILE')) return null;
        $this->expect('T_OPENING_PARENTHESIS');
        [$condition, $this->currentIndex] = (new ParseConditionExpression($this->tokens))->parse($this->currentIndex);
        $this->expect('T_CLOSING_PARENTHESIS');
        $this->expect('T_OPENING_BRACE');
        [$body, $this->currentIndex] = (new ParseCodeBlock($this->tokens))->parse($this->currentIndex);
        $this->expect('T_CLOSING_BRACE');

        return [new WhileLoopNode($condition, $body), $this->currentIndex];
    }
}