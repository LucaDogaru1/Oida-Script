<?php

namespace Oida\Parser\Literals;

use Oida\AST\Literals\BooleanNode;
use Oida\Parser\BaseParser;

class ParseBoolean extends BaseParser
{

    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_TRUE') && !$this->match('T_FALSE')) return null;

        return [new BooleanNode($this->tokens[$this->currentIndex - 1][1]), $this->currentIndex];
    }

}