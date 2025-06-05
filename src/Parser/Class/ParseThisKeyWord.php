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

        if ($this->match('T_ASSIGN')) return null;

        if ($this->match('T_OPENING_PARENTHESIS')) {
            $helperMethod = new HelperMethods($this->tokens);
            [$args, $this->currentIndex] = $helperMethod->checkForMultipleExpressionsInParenthesis($this->currentIndex);
            $this->expect('T_CLOSING_PARENTHESIS');
            $this->expect('T_LINE_END');
            return [new ThisKeywordNode($propertyName, $args), $this->currentIndex];
        }

        $thisNode = new ThisKeywordNode($propertyName, null);

        if ($this->tokens[$this->currentIndex][0] === 'T_CLASS_ACCESS') {
            return $this->checkForClassAccess($thisNode);
        }

        return [$thisNode, $this->currentIndex];
    }


    /**
     * @throws Exception
     */
    private function checkForClassAccess(ThisKeywordNode $target): array
    {
        $this->expect('T_CLASS_ACCESS');

        $this->expect('T_IDENTIFIER');
        $methodName = new IdentifierNode($this->tokens[$this->currentIndex - 1][1]);

        $this->expect('T_OPENING_PARENTHESIS');
        $helperMethod = new HelperMethods($this->tokens);
        [$args, $this->currentIndex] = $helperMethod->checkForMultipleExpressionsInParenthesis($this->currentIndex);
        $this->expect('T_CLOSING_PARENTHESIS');
        $this->expect('T_LINE_END');

        return [new MethodCallNode($target, $methodName, $args), $this->currentIndex];
    }

}