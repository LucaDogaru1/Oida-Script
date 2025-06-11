<?php

namespace Oida\Parser\Class;

use Exception;
use Oida\AST\Class\MethodCallNode;
use Oida\AST\Class\ThisKeywordNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Parser\BaseParser;
use Oida\Parser\HelperMethods\HelperMethods;

class ParseThisKeyWord extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_THIS')) return null;
        $this->expect('T_COLON');
        $this->expect('T_IDENTIFIER');

        $propertyName = new IdentifierNode($this->tokens[$this->currentIndex - 1][1]);

        if ($this->match('T_OPENING_PARENTHESIS')) {
            $helper = new HelperMethods($this->tokens);
            [$args, $this->currentIndex] = $helper->checkForMultipleExpressionsInParenthesis($this->currentIndex);
            $this->expect('T_CLOSING_PARENTHESIS');
            $this->expect('T_LINE_END');

            return [new MethodCallNode(new ThisKeywordNode(), $propertyName, $args), $this->currentIndex];
        }

        return [new ThisKeywordNode($propertyName), $this->currentIndex];
    }
}