<?php

namespace Oida\Parser\Class;

use Exception;
use Oida\AST\Class\ConstructorNode;
use Oida\AST\Class\MethodNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Parser\BaseParser;
use Oida\Parser\HelperMethods\HelperMethods;
use Oida\Parser\ParseCodeBlock;

class ParseClassMethod extends BaseParser
{

    private ?ConstructorNode $constructorNode = null;

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;
        if (!$this->match('T_CLASS_PROPERTY_PUBLIC') && !$this->match('T_CLASS_PROPERTY_PRIVATE')) return null;

        $visibility = $this->tokens[$this->currentIndex - 1][1];

        if(!$this->match('T_METHOD')) return null;

        if(!$this->match('T_IDENTIFIER')) throw new Exception("Du musst der methode schon einen Namen geben ?");
        $methodName = new IdentifierNode($this->tokens[$this->currentIndex - 1][1]);

        $this->expect('T_OPENING_PARENTHESIS');

        $helperMethod = new HelperMethods($this->tokens);
        [$args, $this->currentIndex] = $helperMethod->checkForMultipleExpressionsInParenthesis($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');
        $this->expect('T_OPENING_BRACE');

        [$body, $this->currentIndex] = (new ParseCodeBlock($this->tokens))->parse($this->currentIndex);

        $this->expect('T_CLOSING_BRACE');


        return [new MethodNode($visibility, $methodName, $body, $args), $this->currentIndex];
    }
}