<?php

namespace Oida\Parser\Access;

use Exception;
use Oida\AST\Access\ArrayAccessNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;

class ParseArrayAccess extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_IDENTIFIER')) return null;
        $arrayNameToken = $this->tokens[$this->currentIndex - 1][1];
        $arrayNode = new IdentifierNode($arrayNameToken);

        if(!$this->match('T_OPENING_BRACKET')) return null;

        $expression = (new ParseExpression($this->tokens))->parse($this->currentIndex);
        if (!$expression) {
            throw new \Exception("🛑 \033[1;31mFehlender Index beim Array-Zugriff.\033[0m");
        }

        [$indexNode, $this->currentIndex] = $expression;

        $this->expect('T_CLOSING_BRACKET');

        return [new ArrayAccessNode($arrayNode, $indexNode), $this->currentIndex];
    }

}