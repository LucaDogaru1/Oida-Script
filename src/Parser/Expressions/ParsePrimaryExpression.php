<?php

namespace Oida\Parser\Expressions;

use Exception;
use Oida\Parser\Access\ParseArrayAccess;
use Oida\Parser\Access\ParseArrayPropertyAccess;
use Oida\Parser\Access\ParseAssoArrayAccess;
use Oida\Parser\Api\ParseFetchGet;
use Oida\Parser\BaseParser;
use Oida\Parser\Chaining\ParseChaining;
use Oida\Parser\Class\ParseInitializeObject;
use Oida\Parser\Class\ParseMethodCall;
use Oida\Parser\Class\ParseThisKeyWord;
use Oida\Parser\Database\ParseConnection;
use Oida\Parser\Database\ParseQuery;
use Oida\Parser\DataStructures\ParseArray;
use Oida\Parser\DataStructures\ParseAssoArray;
use Oida\Parser\Function\ParseFunctionCall;
use Oida\Parser\HigherOrderFunctions\ParseFilter;
use Oida\Parser\HigherOrderFunctions\ParseMap;
use Oida\Parser\Literals\ParseBoolean;
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

        $parsers = [
            ParseString::class,
            ParseNumber::class,
            ParseBoolean::class,
            ParseFetchGet::class,
            ParseConnection::class,
            ParseQuery::class,
            ParseArray::class,
            ParseArrayAccess::class,
            ParseAssoArray::class,
            ParseArrayPropertyAccess::class,
            ParseAssoArrayAccess::class,
            ParseFilter::class,
            ParseMap::class,
            ParseMethodCall::class,
            ParseFunctionCall::class,
            ParseThisKeyWord::class,
            ParseInitializeObject::class,
            ParseIdentifier::class,
        ];

        foreach ($parsers as $parserClass) {
            $parser = new $parserClass($this->tokens);

            $result = match ($parserClass) {
                ParseQuery::class => $parser->parse($this->currentIndex, true),
                ParseFunctionCall::class, ParseMethodCall::class => $parser->parse($this->currentIndex, false),
                default => $parser->parse($this->currentIndex),
            };


            if ($result) {
                [$baseNode, $this->currentIndex] = $result;

                $chain = new ParseChaining($this->tokens, $baseNode);
                [$fullNode, $this->currentIndex] = $chain->parse($this->currentIndex);

                return [$fullNode, $this->currentIndex];
            }
        }

        return null;
    }
}
