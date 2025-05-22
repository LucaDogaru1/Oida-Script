<?php

namespace Parser;


use Exception;
use Oida\Parser\Literals\ParseNumber;
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