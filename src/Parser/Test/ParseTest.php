<?php

namespace Oida\Parser\Test;

use Exception;
use Oida\AST\Test\TestNode;
use Oida\Parser\BaseParser;
use Oida\Parser\HelperMethods\HelperMethods;
use Oida\Parser\ParseCodeBlock;

class ParseTest extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_TEST')) return null;

        $this->expect('T_IDENTIFIER');
        $name = $this->tokens[$this->currentIndex -1][1];

        $this->expect('T_OPENING_PARENTHESIS');

        $helperMethod = new HelperMethods($this->tokens);
        [$args, $this->currentIndex] = $helperMethod->checkForMultipleExpressionsInParenthesis($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');

        $this->expect('T_OPENING_BRACE');

        $codeBlock = (new ParseCodeBlock($this->tokens))->parse($this->currentIndex);
        if(!$codeBlock) throw new Exception('Musst schon was in den Test Schreiben');

        [$body, $this->currentIndex] = $codeBlock;


        $this->expect('T_CLOSING_BRACE');

        return [new TestNode($name, $args, $body), $this->currentIndex];
    }

}