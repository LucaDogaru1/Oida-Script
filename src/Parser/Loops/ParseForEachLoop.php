<?php

namespace Oida\Parser\Loops;

use Exception;
use Oida\AST\Loops\ForEachLoopNode;
use Oida\Parser\BaseParser;
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
        $this->expect('T_IDENTIFIER');

        $arrayName = $this->tokens[$this->currentIndex - 1][1];

        $this->expect('T_AS');
        $this->expect('T_IDENTIFIER');

        $itemName = $this->tokens[$this->currentIndex - 1][1];

        $this->expect('T_CLOSING_PARENTHESIS');
        $this->expect('T_OPENING_BRACE');

        [$body, $this->currentIndex] = (new ParseCodeBlock($this->tokens))->parse($this->currentIndex);
        $this->expect('T_CLOSING_BRACE');

        return [new ForEachLoopNode($arrayName, $itemName, $body), $this->currentIndex];

    }

}