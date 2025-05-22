<?php

namespace Oida\Parser\Print;

use Exception;
use Oida\AST\PrintNode;
use Oida\Parser\BaseParser;
use Oida\Parser\HelperMethods\HelperMethods;

class ParsePrint extends BaseParser
{


    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;
        if (!$this->match('T_PRINT')) {
            return null;
        }
        $this->expect('T_OPENING_PARENTHESIS');

        $helperMethod = new HelperMethods($this->tokens);
        [$values, $this->currentIndex] = $helperMethod->checkForMultipleExpressionsInParenthesis($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');

        $this->expect('T_LINE_END');

        $node = new PrintNode($values);
        return [$node, $this->currentIndex];

    }
}