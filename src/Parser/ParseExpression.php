<?php

namespace Oida\Parser;

use Exception;
use Oida\AST\Class\ThisKeywordStatementNode;
use Oida\Parser\Class\ParseInitializeObject;
use Oida\Parser\Class\ParseMethodCall;
use Oida\Parser\Class\ParseThisKeyWord;

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

        $number =  (new ParseNumber($this->tokens))->parse($this->currentIndex);
        if($number) return $number;

        $thisKeyword = (new ParseThisKeyWord($this->tokens))->parse($this->currentIndex);

        if($thisKeyword) return $thisKeyword;

        $print = (new ParsePrint($this->tokens))->parse($this->currentIndex);
        if($print) return $print;

        $object = (new ParseInitializeObject($this->tokens))->parse($this->currentIndex);
        if($object) return $object;

        $methodCall = (new ParseMethodCall($this->tokens))->parse($this->currentIndex);
        if($methodCall) return $methodCall;

        $identifier = (new ParseIdentifier($this->tokens))->parse($this->currentIndex);
        if($identifier) return $identifier;

        return null;
    }

}