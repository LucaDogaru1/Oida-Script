<?php

namespace Oida\Parser\Class;

use Oida\AST\Class\MethodCallNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Parser\BaseParser;
use Oida\Parser\HelperMethods\HelperMethods;

class ParseMethodCall extends BaseParser
{

    /**
     * @throws \Exception
     */
    public function parse(int $tokenIndex, bool $isStatement = false): ?array
    {
        $this->currentIndex = $tokenIndex;
        if(!$this->match('T_IDENTIFIER')) return null;
        $object =  new IdentifierNode($this->tokens[$this->currentIndex - 1][1]);

        if(!$this->match('T_CLASS_ACCESS')) return null;

        $this->expect('T_IDENTIFIER');
        $methodName =  new IdentifierNode($this->tokens[$this->currentIndex - 1][1]);

        $this->expect('T_OPENING_PARENTHESIS');

        $helperMethod = new HelperMethods($this->tokens);
        [$args, $this->currentIndex] = $helperMethod->checkForMultipleExpressionsInParenthesis($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');

        $methodCallNode = new MethodCallNode($object, $methodName, $args);

        if ($isStatement) {
            $this->expect('T_LINE_END');
        }

        return [$methodCallNode, $this->currentIndex];
    }

}