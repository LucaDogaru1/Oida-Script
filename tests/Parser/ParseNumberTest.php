<?php

namespace Parser;


use Exception;
use Oida\Lexer\Lexer;
use Oida\Parser\ParseNumber;
use PHPUnit\Framework\TestCase;
use Tests\Parser\ParserTestCase;

class ParseNumberTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_parse_number()
    {
        $tokens = $this->tokenize(12);
        $parser = new ParseNumber($tokens);


        $this->assertEquals('number', $parser->parse(0)[0]->getType());

    }
}