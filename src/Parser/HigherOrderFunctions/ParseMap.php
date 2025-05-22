<?php

namespace Oida\Parser\HigherOrderFunctions;

use Exception;
use Oida\AST\HigherOrderFunction\MapNode;
use Oida\Parser\BaseParser;
use Oida\Parser\ParseCodeBlock;

class ParseMap extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex)
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_MAP')) return null;
        $this->expect('T_ARROW');

        $this->expect('T_IDENTIFIER');
        $arrayName = $this->tokens[$this->currentIndex -1][1];

        $this->expect('T_OPENING_PARENTHESIS');

        $this->expect('T_IDENTIFIER');
        $itemName = $this->tokens[$this->currentIndex -1][1];

        $this->expect('T_ARROW');

        [$body , $this->currentIndex] = (new ParseCodeBlock($this->tokens))->parse($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');

        return [new MapNode($arrayName, $itemName, $body), $this->currentIndex];
    }
}