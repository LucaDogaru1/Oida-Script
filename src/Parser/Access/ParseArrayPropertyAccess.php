<?php

namespace Oida\Parser\Access;

use Exception;
use Oida\AST\Access\PropertyAccessNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;

class ParseArrayPropertyAccess extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_IDENTIFIER')) return null;
        $arrayName = $this->tokens[$this->currentIndex -1][1];

        if(!$this->match('T_DOT')) return null;
        $this->expect('T_IDENTIFIER');

        $propertyName = $this->tokens[$this->currentIndex -1][1];

        if(!$this->match('T_OPENING_PARENTHESIS')) return [new PropertyAccessNode($arrayName, $propertyName, null), $this->currentIndex];

        [$value, $this->currentIndex] = (new ParseExpression($this->tokens))->parse($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');
        return [new PropertyAccessNode($arrayName, $propertyName, $value), $this->currentIndex];
    }
}