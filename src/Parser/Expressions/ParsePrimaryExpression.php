<?php

namespace Oida\Parser\Expressions;

use Exception;
use Oida\Parser\BaseParser;
use Oida\Parser\Class\ParseInitializeObject;
use Oida\Parser\Class\ParseMethodCall;
use Oida\Parser\Class\ParseThisKeyWord;
use Oida\Parser\DataStructures\ParseArray;
use Oida\Parser\HigherOrderFunctions\ParseFilter;
use Oida\Parser\HigherOrderFunctions\ParseMap;
use Oida\Parser\ParseFunctionCall;
use Oida\Parser\ParseIdentifier;
use Oida\Parser\ParseNumber;
use Oida\Parser\ParseString;

class ParsePrimaryExpression extends BaseParser
{
    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        $string = (new ParseString($this->tokens))->parse($this->currentIndex);
        if ($string) return $string;

        $number = (new ParseNumber($this->tokens))->parse($this->currentIndex);
        if ($number) return $number;

        $array = (new ParseArray($this->tokens))->parse($this->currentIndex);
        if ($array) return $array;

        $filter = (new ParseFilter($this->tokens))->parse($this->currentIndex);
        if ($filter) return $filter;

        $map = (new ParseMap($this->tokens))->parse($this->currentIndex);
        if($map) return $map;

        $methodCall = (new ParseMethodCall($this->tokens))->parse($this->currentIndex);
        if ($methodCall) return $methodCall;

        $functionCall = (new ParseFunctionCall($this->tokens))->parse($this->currentIndex);
        if ($functionCall) return $functionCall;

        $thisKeyword = (new ParseThisKeyWord($this->tokens))->parse($this->currentIndex);
        if ($thisKeyword) return $thisKeyword;

        $object = (new ParseInitializeObject($this->tokens))->parse($this->currentIndex);
        if ($object) return $object;

        $identifier = (new ParseIdentifier($this->tokens))->parse($this->currentIndex);
        if ($identifier) return $identifier;

        return null;
    }
}
