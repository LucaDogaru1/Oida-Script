<?php

namespace Oida\Parser\Expressions;

use Exception;
use Oida\Parser\BaseParser;
use Oida\Parser\BinaryExpression\ParseBinaryOperation;

class ParseExpression extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        return (new ParseBinaryOperation($this->tokens))->parse($this->currentIndex, 0);
    }

}