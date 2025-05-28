<?php

namespace Oida\Parser\Literals;

use Exception;
use Oida\AST\Literals\NumberNode;
use Oida\Parser\BaseParser;

class ParseNumber extends BaseParser
{
    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_NUMBER')) return null;

        $token = $this->tokens[$this->currentIndex - 1];
        $value = $token[1];

        $node = new NumberNode($value);
        return [$node, $this->currentIndex];
    }

}