<?php

namespace Oida\Parser\Class;


use Exception;
use Oida\AST\Class\ClassVariableNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\AST\NullExpressionNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;

class ParseClassProperty extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_CLASS_PROPERTY_PUBLIC') && !$this->match('T_CLASS_PROPERTY_PRIVATE')) return null;
        $visibility = $this->tokens[$this->currentIndex - 1][1];

        if(!$this->match('T_IDENTIFIER')) return null;
        $variableName = new IdentifierNode($this->tokens[$this->currentIndex - 1][1]);

        if ($this->match('T_ASSIGN')) {
            [$nodeValue, $this->currentIndex] = (new ParseExpression($this->tokens))->parse($this->currentIndex);
        } else {
            $nodeValue = new NullExpressionNode();
        }

        $this->expect('T_LINE_END');
        return [new ClassVariableNode($visibility, $variableName, $nodeValue), $this->currentIndex];

    }

}