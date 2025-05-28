<?php

namespace Oida\Parser\Api;

use Exception;
use Oida\AST\Api\FetchNode;
use Oida\Parser\BaseParser;

class ParseFetchGet extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_FETCH')) return null;

        $this->expect('T_OPENING_PARENTHESIS');

        if($this->tokens[$this->currentIndex][0] !== 'T_IDENTIFIER' && $this->tokens[$this->currentIndex][0] !== 'T_STRING') return null;
        $apiLink = $this->tokens[$this->currentIndex][1];
        $this->currentIndex ++;

        $this->expect('T_CLOSING_PARENTHESIS');

        return [new FetchNode($apiLink), $this->currentIndex];
    }

}