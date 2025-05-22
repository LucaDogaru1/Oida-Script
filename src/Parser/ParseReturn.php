<?php

namespace Oida\Parser;

use Exception;
use Oida\AST\ReturnNode;
use Oida\Parser\Expressions\ParseExpression;

class ParseReturn extends BaseParser
{
    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_RETURN')) return null;

        [$exprNode, $this->currentIndex] = (new ParseExpression($this->tokens))->parse($this->currentIndex);
        $this->expect('T_LINE_END');

        return [new ReturnNode($exprNode), $this->currentIndex];
    }
}