<?php

namespace Oida\Parser\Function;

use Exception;
use Oida\AST\FunctionCallNode;
use Oida\Parser\BaseParser;
use Oida\Parser\HelperMethods\HelperMethods;

class ParseFunctionCall extends BaseParser
{
    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;
        if (!$this->match('T_IDENTIFIER')) return null;
        $funcName = $this->tokens[$this->currentIndex - 1][1];

        if (!$this->match('T_OPENING_PARENTHESIS')) return null;

        $helperMethod = new HelperMethods($this->tokens);
        [$args, $this->currentIndex] = $helperMethod->checkForMultipleExpressionsInParenthesis($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');
        $this->expect('T_LINE_END');

        $functionCallNode =  new FunctionCallNode($funcName, $args);
        return [$functionCallNode, $this->currentIndex];
    }
}