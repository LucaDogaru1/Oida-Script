<?php

namespace Oida\Parser\DataStructures;

use Exception;
use Oida\AST\DataStructureNode\ArrayNode;
use Oida\Parser\BaseParser;
use Oida\Parser\ParseExpression;

class ParseArray extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_OPENING_BRACKET')) return null;

        $values = [];

        if ($this->currentToken()[0] === 'T_CLOSING_BRACKET') {
            $this->advance();
            return [new ArrayNode($values), $this->currentIndex];
        }

        do {
            [$valueNode, $this->currentIndex] = (new ParseExpression($this->tokens))->parse($this->currentIndex);
            $values[] = $valueNode;
        } while ($this->match('T_SEPARATOR'));

        $this->expect('T_CLOSING_BRACKET');

        return [new ArrayNode($values), $this->currentIndex];
    }

}