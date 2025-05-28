<?php

namespace Oida\Parser\DataStructures;

use Exception;
use Oida\AST\DataStructure\AssoArrayNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;

class ParseAssoArray extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex)
    {
        $this->currentIndex = $tokenIndex;
        if (!$this->match('T_OPENING_BRACE')) return null;

        $entries = [];

        while (true) {
            $expressionKey = (new ParseExpression($this->tokens))->parse($this->currentIndex);
            if (!$expressionKey) break;
            [$key, $this->currentIndex] = $expressionKey;

            if (!$this->match('T_COLON')) throw new Exception("🛑 \033[1;31mNach dem Key brauchst schon ein ':'\033[0m");


            $expressionValue = (new ParseExpression($this->tokens))->parse($this->currentIndex);
            if (!$expressionValue)throw new Exception("🛑 \033[1;31mBrauchst einen Value für Key\033[0m");
            [$value, $this->currentIndex] = $expressionValue;
            $entries[] = [$key, $value];

            if ($this->match('T_SEPARATOR')) continue;
            break;
        }

        $this->expect('T_CLOSING_BRACE');
        return [new AssoArrayNode($entries), $this->currentIndex];
    }
}