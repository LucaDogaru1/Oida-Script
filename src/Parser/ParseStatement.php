<?php

namespace Oida\Parser;

use Exception;
use http\Env;
use Oida\AST\Database\QueryNode;
use Oida\AST\Expression\VoidExpressionNode;
use Oida\AST\HigherOrderFunction\FilterNode;
use Oida\AST\HigherOrderFunction\MapNode;
use Oida\Environment\Environment;
use Oida\Parser\Assignment\ParseAssignment;
use Oida\Parser\Class\ParseClass;
use Oida\Parser\Class\ParseClassMethod;
use Oida\Parser\Class\ParseClassProperty;
use Oida\Parser\Class\ParseConstructor;
use Oida\Parser\Class\ParseMethodCall;
use Oida\Parser\Class\ParseThisKeyWordStatement;
use Oida\Parser\Database\ParseQuery;
use Oida\Parser\Expressions\ParseExpression;
use Oida\Parser\Function\ParseFunction;
use Oida\Parser\Function\ParseFunctionCall;
use Oida\Parser\IfStatement\ParseIfStatement;
use Oida\Parser\Import\ParseImport;
use Oida\Parser\Loops\ParseForEachLoop;
use Oida\Parser\Loops\ParseForLoop;
use Oida\Parser\Loops\ParseWhileLoop;
use Oida\Parser\Print\ParsePrint;
use Oida\Parser\Return\ParseReturn;
use Oida\Parser\Test\ParseAssert;
use Oida\Parser\Test\ParseTest;
use Oida\Parser\Variable\ParseVariable;

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

        $import = (new ParseImport($this->tokens))->parse($this->currentIndex);
        if($import) return $import;

        $class = (new ParseClass($this->tokens))->parse($this->currentIndex);
        if($class) return $class;

        $constructor = (new ParseConstructor($this->tokens))->parse($this->currentIndex);
        if($constructor) return $constructor;

        $thisKeywordStatement = (new ParseThisKeyWordStatement($this->tokens))->parse($this->currentIndex);
        if($thisKeywordStatement)  return $thisKeywordStatement;

        $query = (new ParseQuery($this->tokens))->parse($this->currentIndex, false);
        if ($query) return $query;

        $method = (new ParseClassMethod($this->tokens))->parse($this->currentIndex);
        if($method) return $method;

        $methodCall = (new ParseMethodCall($this->tokens))->parse($this->currentIndex, true);
        if($methodCall) return $methodCall;

        $function = (new ParseFunction($this->tokens))->parse($this->currentIndex);
        if($function) return $function;

        $functionCall = (new ParseFunctionCall($this->tokens))->parse($this->currentIndex, true);
        if ($functionCall) return $functionCall;

        $classVariable = (new ParseClassProperty($this->tokens))->parse($this->currentIndex);
        if($classVariable) return $classVariable;

        $variable = (new ParseVariable($this->tokens))->parse($this->currentIndex);
        if($variable) return $variable;

        $assignment = (new ParseAssignment($this->tokens, $this->env))->parse($this->currentIndex);
        if($assignment) return $assignment;

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

        $test = (new ParseTest($this->tokens))->parse($this->currentIndex);
        if($test) return $test;

        $assert = (new ParseAssert($this->tokens))->parse($this->currentIndex);
        if($assert) return $assert;

        $expression = (new ParseExpression($this->tokens))->parse($this->currentIndex);
        if ($expression) {
            if ($expression[0] instanceof MapNode || $expression[0] instanceof FilterNode) {
                throw new Exception("🛑 \033[1;31m'map'\033[0m oder \033[1;31m'filter'\033[0m darfst du nicht einfach so stehen lassen. \033[1;33mPack's in eine Variable, mein Bester.\033[0m");
            }
            return $expression;
        }


        return null;
    }

}