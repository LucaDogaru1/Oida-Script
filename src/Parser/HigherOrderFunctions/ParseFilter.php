<?php

namespace Oida\Parser\HigherOrderFunctions;

use Oida\AST\HigherOrderFunction\FilterNode;
use Oida\Parser\BaseParser;
use Oida\Parser\ConditionExpression\ParseConditionExpression;

class ParseFilter extends BaseParser
{

    /**
     * @throws \Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;
        if (!$this->match('T_FILTER')) return null;

        $this->expect('T_FILTER_ARROW');

        $this->expect('T_IDENTIFIER');
        $arrayName = $this->tokens[$this->currentIndex - 1][1];

        $this->expect('T_OPENING_PARENTHESIS');

        $this->expect('T_IDENTIFIER');
        $itemName = $this->tokens[$this->currentIndex - 1][1];

        $this->currentIndex--;
        $conditionParser = new ParseConditionExpression($this->tokens);
        $parsed = $conditionParser->parse($this->currentIndex);


        if (!$parsed) {
            throw new \Exception("🛑 Du musst schon eine Bedingung beim Filter angeben ??");
        }

        [$condition, $this->currentIndex] = $parsed;
        $this->expect('T_CLOSING_PARENTHESIS');

        return [new FilterNode($arrayName, $itemName, $condition), $this->currentIndex];
    }
}