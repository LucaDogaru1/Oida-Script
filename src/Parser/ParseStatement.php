<?php

namespace Oida\Parser;

use Exception;
use Oida\Parser\Class\ParseClass;
use Oida\Parser\Class\ParseClassMethod;
use Oida\Parser\Class\ParseClassProperty;
use Oida\Parser\Class\ParseConstructor;
use Oida\Parser\Class\ParseThisKeyWordStatement;

class ParseStatement extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        $class = (new ParseClass($this->tokens))->parse($this->currentIndex);
        if($class) return $class;

        $constructor = (new ParseConstructor($this->tokens))->parse($this->currentIndex);
        if($constructor) return $constructor;

        $thisKeywordStatement = (new ParseThisKeyWordStatement($this->tokens))->parse($this->currentIndex);
        if($thisKeywordStatement)  return $thisKeywordStatement;

        $method = (new ParseClassMethod($this->tokens))->parse($this->currentIndex);
        if($method) return $method;

        $classVariable = (new ParseClassProperty($this->tokens))->parse($this->currentIndex);
        if($classVariable) return $classVariable;

        $variable = (new ParseVariable($this->tokens))->parse($this->currentIndex);
        if($variable) return $variable;

        $expression = (new ParseExpression($this->tokens))->parse($this->currentIndex);
        if($expression) return $expression;

        return null;
    }

}