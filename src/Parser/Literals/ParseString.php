<?php

namespace Oida\Parser\Literals;

use Exception;
use Oida\AST\Literals\StringNode;
use Oida\Parser\BaseParser;

class ParseString extends BaseParser
{
    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {

        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_STRING')) {
            return null;
        }
        $token = $this->tokens[$this->currentIndex - 1];
        $value = $token[1];


        $node = new StringNode($value);
        return [$node, $this->currentIndex];
    }

}