<?php

namespace Oida\Parser\Function;

use Exception;
use Oida\AST\Function\FunctionNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Parser\BaseParser;
use Oida\Parser\HelperMethods\HelperMethods;
use Oida\Parser\ParseCodeBlock;

class ParseFunction extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex):?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_METHOD')) return null;

        $this->expect('T_IDENTIFIER');
        $methodName = new IdentifierNode($this->tokens[$this->currentIndex - 1][1]);

        $this->expect('T_OPENING_PARENTHESIS');

        $helperMethod = new HelperMethods($this->tokens);
        [$args, $this->currentIndex] = $helperMethod->checkForMultipleExpressionsInParenthesis($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');
        $this->expect('T_OPENING_BRACE');

        [$body, $this->currentIndex] = (new ParseCodeBlock($this->tokens))->parse($this->currentIndex);

        $this->expect('T_CLOSING_BRACE');

        return [new FunctionNode($methodName, $body, $args), $this->currentIndex];


    }

}