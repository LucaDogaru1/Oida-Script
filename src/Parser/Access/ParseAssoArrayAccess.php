<?php

namespace Oida\Parser\Access;

use Exception;
use Oida\AST\Access\AssoArrayAccessNode;
use Oida\AST\Literals\NumberNode;
use Oida\AST\Literals\StringNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;

class ParseAssoArrayAccess extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_IDENTIFIER')) return null;
        $assoArrayName = $this->tokens[$this->currentIndex -1][1];

        if(!$this->match('T_DOT')) return null;

        if ($this->match('T_IDENTIFIER')) {
            $keyName = $this->tokens[$this->currentIndex - 1][1];
            $propertyNode = new StringNode($keyName);
        }  else {
            $expression = (new ParseExpression($this->tokens))->parse($this->currentIndex);
            if (!$expression) return null;
            [$propertyNode, $this->currentIndex] = $expression;
        }

        return [new AssoArrayAccessNode($assoArrayName, $propertyNode), $this->currentIndex];
    }
}