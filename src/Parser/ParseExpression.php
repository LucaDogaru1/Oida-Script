<?php

namespace Oida\Parser;

use Exception;
use Oida\Parser\BinaryExpression\ParseBinaryOperation;
use Oida\Parser\Class\ParseInitializeObject;
use Oida\Parser\Class\ParseMethodCall;
use Oida\Parser\Class\ParseThisKeyWord;
use Oida\Parser\DataStructures\ParseArray;

class ParseExpression extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        $string = (new ParseString($this->tokens))->parse($this->currentIndex);
        if($string) return $string;

        $array = (new ParseArray($this->tokens))->parse($this->currentIndex);
        if($array) return $array;

        $methodCall = (new ParseMethodCall($this->tokens))->parse($this->currentIndex);
        if($methodCall) return $methodCall;

        $functionCall = (new ParseFunctionCall($this->tokens))->parse($this->currentIndex);
        if($functionCall) return $functionCall;

        $binaryOperation = (new ParseBinaryOperation($this->tokens))->parse($this->currentIndex, 0);
        if($binaryOperation) return $binaryOperation;

        $number =  (new ParseNumber($this->tokens))->parse($this->currentIndex);
        if($number) return $number;

        $thisKeyword = (new ParseThisKeyWord($this->tokens))->parse($this->currentIndex);
        if($thisKeyword) return $thisKeyword;

        $object = (new ParseInitializeObject($this->tokens))->parse($this->currentIndex);
        if($object) return $object;


        $identifier = (new ParseIdentifier($this->tokens))->parse($this->currentIndex);
        if($identifier) return $identifier;

        return null;
    }

}