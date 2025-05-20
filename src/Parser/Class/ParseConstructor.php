<?php

namespace Oida\Parser\Class;

use Exception;
use Oida\AST\Class\ConstructorNode;
use Oida\Parser\BaseParser;
use Oida\Parser\HelperMethods\HelperMethods;
use Oida\Parser\ParseCodeBlock;

class ParseConstructor extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_CONSTRUCTOR')) return null;
        $name = $this->tokens[$this->currentIndex - 1][1];


        $this->expect('T_OPENING_PARENTHESIS');
        $helperMethod = new HelperMethods($this->tokens);
        [$args, $this->currentIndex] = $helperMethod->checkForMultipleExpressionsInParenthesis($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');

        $this->expect('T_OPENING_BRACE');

        [$body, $this->currentIndex] = (new ParseCodeBlock($this->tokens))->parse($this->currentIndex);

        $this->expect('T_CLOSING_BRACE');

        $constructorNode = new ConstructorNode($name, $args, $body);
        return [$constructorNode, $this->currentIndex];
    }

}