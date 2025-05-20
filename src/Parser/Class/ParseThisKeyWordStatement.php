<?php

namespace Oida\Parser\Class;

use Exception;
use Oida\AST\Class\ThisKeywordStatementNode;
use Oida\AST\IdentifierNode;
use Oida\Parser\BaseParser;
use Oida\Parser\ParseExpression;

class ParseThisKeyWordStatement extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_THIS')) return null;

        $this->expect('T_CLASS_PROPERTY_ACCESS');

        $this->expect('T_IDENTIFIER');
        $propertyName = new IdentifierNode($this->tokens[$this->currentIndex - 1][1]);

        if (!$this->match('T_ASSIGN')) return null;

        [$valueExpression, $this->currentIndex] = (new ParseExpression($this->tokens))->parse($this->currentIndex);
        $this->expect('T_LINE_END');
        return [new ThisKeywordStatementNode($propertyName, $valueExpression), $this->currentIndex];

    }
}