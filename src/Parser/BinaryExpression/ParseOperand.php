<?php

namespace Oida\Parser\BinaryExpression;

use Exception;
use Oida\Parser\BaseParser;
use Oida\Parser\ParseIdentifier;
use Oida\Parser\ParseNumber;

class ParseOperand extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;
        $number =  (new ParseNumber($this->tokens))->parse($this->currentIndex);
        if($number) return $number;

        $identifier = (new ParseIdentifier($this->tokens))->parse($this->currentIndex);
        if($identifier) return $identifier;

        return null;
    }
}