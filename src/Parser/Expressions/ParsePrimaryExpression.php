<?php

namespace Oida\Parser\Expressions;

use Exception;
use Oida\AST\Access\PropertyAccessNode;
use Oida\Parser\Access\ParseArrayAccess;
use Oida\Parser\Access\ParseArrayPropertyAccess;
use Oida\Parser\BaseParser;
use Oida\Parser\Class\ParseInitializeObject;
use Oida\Parser\Class\ParseMethodCall;
use Oida\Parser\Class\ParseThisKeyWord;
use Oida\Parser\DataStructures\ParseArray;
use Oida\Parser\Function\ParseFunctionCall;
use Oida\Parser\HigherOrderFunctions\ParseFilter;
use Oida\Parser\HigherOrderFunctions\ParseMap;
use Oida\Parser\Literals\ParseIdentifier;
use Oida\Parser\Literals\ParseNumber;
use Oida\Parser\Literals\ParseString;

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

        $arrayAccess = (new ParseArrayAccess($this->tokens))->parse($this->currentIndex);
        if($arrayAccess) return $arrayAccess;

        $propertyAccess = (new ParseArrayPropertyAccess($this->tokens))->parse($this->currentIndex);
        if($propertyAccess) return $propertyAccess;

        $filter = (new ParseFilter($this->tokens))->parse($this->currentIndex);
        if ($filter) return $filter;

        $map = (new ParseMap($this->tokens))->parse($this->currentIndex);
        if($map) return $map;

        $methodCall = (new ParseMethodCall($this->tokens))->parse($this->currentIndex, false);
        if ($methodCall) return $methodCall;

        $functionCall = (new ParseFunctionCall($this->tokens))->parse($this->currentIndex, false);
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
