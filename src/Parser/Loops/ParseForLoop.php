<?php

namespace Oida\Parser\Loops;

use Exception;
use Oida\AST\Loops\ForLoopNode;
use Oida\Parser\BaseParser;
use Oida\Parser\BinaryExpression\ParseBinaryOperation;
use Oida\Parser\ConditionExpression\ParseConditionExpression;
use Oida\Parser\ParseCodeBlock;
use Oida\Parser\Variable\ParseVariable;

class ParseForLoop extends BaseParser
{
    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_FOR')) return null;
        $this->expect('T_OPENING_PARENTHESIS');

        [$variable, $this->currentIndex] = (new ParseVariable($this->tokens))->parse($this->currentIndex);
        [$condition, $this->currentIndex] = (new ParseConditionExpression($this->tokens))->parse($this->currentIndex);
        $this->expect('T_LINE_END');
        [$binaryOperation, $this->currentIndex] = (new ParseBinaryOperation($this->tokens))->parse($this->currentIndex, 0);
        $this->expect('T_CLOSING_PARENTHESIS');
        $this->expect('T_OPENING_BRACE');
        [$body, $this->currentIndex] = (new ParseCodeBlock($this->tokens))->parse($this->currentIndex);
        $this->expect('T_CLOSING_BRACE');

        return [new ForLoopNode($variable, $condition, $binaryOperation, $body), $this->currentIndex];
    }

}