<?php

namespace Oida\Parser\HelperMethods;

use Exception;
use Oida\Parser\BaseParser;
use Oida\Parser\ParseExpression;

class HelperMethods extends BaseParser
{

    /**
     * @throws Exception
     */
    public function checkForMultipleExpressionsInParenthesis(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        $values = [];
        while (true) {
            $expressionResult = (new ParseExpression($this->tokens))->parse($this->currentIndex);
            if (!$expressionResult) break;

            [$valueNode, $nextIndex] = $expressionResult;
            $this->currentIndex = $nextIndex;

            $values[] = $valueNode;

            if (!$this->match('T_SEPARATOR')) break;
        }

        return [$values, $this->currentIndex];
    }

}