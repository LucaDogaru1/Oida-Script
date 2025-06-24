<?php

namespace Oida\Parser\Mock;

use Exception;
use Oida\AST\Mock\MockNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;
use Oida\Parser\HelperMethods\HelperMethods;

class ParseMock extends BaseParser
{


    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_MOCK')) return null;

        $this->expect('T_OPENING_PARENTHESIS');
        $this->expect('T_STRING');
        $methodName = $this->tokens[$this->currentIndex - 1][1];
        $this->expect('T_CLOSING_PARENTHESIS');
        $this->expect('T_IDENTIFIER');
        if ($this->tokens[$this->currentIndex - 1][1] !== "mit") throw new Exception("dir fehlt 'mit' nach der closing klammer vom Mock yow");
        $this->expect('T_OPENING_PARENTHESIS');

        [$returnValue, $this->currentIndex] = (new ParseExpression($this->tokens))->parse($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');

        return [new MockNode($methodName, $returnValue), $this->currentIndex];
    }
}