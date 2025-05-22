<?php

namespace Oida\Parser;

use Exception;
use Oida\Parser\Class\ParseClass;
use Oida\Parser\Class\ParseClassMethod;
use Oida\Parser\Class\ParseClassProperty;
use Oida\Parser\Class\ParseConstructor;
use Oida\Parser\Class\ParseThisKeyWordStatement;
use Oida\Parser\Expressions\ParseExpression;
use Oida\Parser\IfStatement\ParseIfStatement;
use Oida\Parser\Loops\ParseForEachLoop;
use Oida\Parser\Loops\ParseForLoop;
use Oida\Parser\Loops\ParseWhileLoop;

class ParseStatement extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        $return = (new ParseReturn($this->tokens))->parse($this->currentIndex);
        if ($return) return $return;

        $class = (new ParseClass($this->tokens))->parse($this->currentIndex);
        if($class) return $class;

        $constructor = (new ParseConstructor($this->tokens))->parse($this->currentIndex);
        if($constructor) return $constructor;

        $thisKeywordStatement = (new ParseThisKeyWordStatement($this->tokens))->parse($this->currentIndex);
        if($thisKeywordStatement)  return $thisKeywordStatement;

        $method = (new ParseClassMethod($this->tokens))->parse($this->currentIndex);
        if($method) return $method;

        $function = (new ParseFunction($this->tokens))->parse($this->currentIndex);
        if($function) return $function;

        $classVariable = (new ParseClassProperty($this->tokens))->parse($this->currentIndex);
        if($classVariable) return $classVariable;

        $variable = (new ParseVariable($this->tokens))->parse($this->currentIndex);
        if($variable) return $variable;

        $print = (new ParsePrint($this->tokens))->parse($this->currentIndex);
        if($print) return $print;

        $if = (new ParseIfStatement($this->tokens))->parse($this->currentIndex);
        if($if) return $if;

        $forLoop = (new ParseForLoop($this->tokens))->parse($this->currentIndex);
        if($forLoop) return $forLoop;

        $whileLoop = (new ParseWhileLoop($this->tokens))->parse($this->currentIndex);
        if($whileLoop) return $whileLoop;

        $forEach = (new ParseForEachLoop($this->tokens))->parse($this->currentIndex);
        if($forEach) return $forEach;

        $expression = (new ParseExpression($this->tokens))->parse($this->currentIndex);
        if($expression) return $expression;

        return null;
    }

}