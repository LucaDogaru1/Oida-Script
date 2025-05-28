<?php

namespace Oida\Parser\Class;

use Exception;
use Oida\AST\Class\ObjectNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Parser\BaseParser;
use Oida\Parser\HelperMethods\HelperMethods;

class ParseInitializeObject extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_NEW')) return null;

        $this->expect('T_IDENTIFIER');
        $objectName = (new IdentifierNode($this->tokens[$this->currentIndex -1][1]));
        $this->expect('T_OPENING_PARENTHESIS');

        $helperMethod = new HelperMethods($this->tokens);
        [$constructor, $this->currentIndex] = $helperMethod->checkForMultipleExpressionsInParenthesis($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');

        $initialize = (new ObjectNode($objectName, $constructor));

        return [$initialize, $this->currentIndex];
    }

}