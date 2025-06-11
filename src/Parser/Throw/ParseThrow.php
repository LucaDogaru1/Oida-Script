<?php

namespace Oida\Parser\Throw;

use Exception;
use Oida\AST\Throw\ThrowNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Literals\ParseString;

class ParseThrow extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex):?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_THROW')) return null;

        $this->expect('T_OPENING_PARENTHESIS');
        $this->expect('T_STRING');
        [$message, $this->currentIndex] = (new ParseString($this->tokens))->parse($this->currentIndex -1);
        $this->expect('T_CLOSING_PARENTHESIS');
        $this->expect('T_LINE_END');

        return [new ThrowNode($message), $this->currentIndex];
    }
}