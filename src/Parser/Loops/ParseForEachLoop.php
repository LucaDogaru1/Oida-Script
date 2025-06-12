<?php

namespace Oida\Parser\Loops;

use Exception;
use Oida\AST\Loops\ForEachLoopNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;
use Oida\Parser\ParseCodeBlock;

class ParseForEachLoop extends BaseParser
{
    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_FOREACH')) return null;
        $this->expect('T_OPENING_PARENTHESIS');

        $expr = (new ParseExpression($this->tokens))->parse($this->currentIndex);
        if (!$expr) throw new Exception("🛑 Ausdruck für Array ungültig");

        [$arrayNode, $this->currentIndex] = $expr;

        $this->expect('T_AS');

        $keyName = null;

        $this->expect('T_IDENTIFIER');
        $firstName = $this->tokens[$this->currentIndex - 1][1];

        if ($this->match('T_ARROW')) {
            $this->expect('T_IDENTIFIER');
            $keyName = $firstName;
            $itemName = $this->tokens[$this->currentIndex - 1][1];
        } else {
            $itemName = $firstName;
        }

        $this->expect('T_CLOSING_PARENTHESIS');
        $this->expect('T_OPENING_BRACE');

        [$body, $this->currentIndex] = (new ParseCodeBlock($this->tokens))->parse($this->currentIndex);
        $this->expect('T_CLOSING_BRACE');

        return [new ForEachLoopNode($arrayNode, $itemName, $body, $keyName), $this->currentIndex];

    }

}