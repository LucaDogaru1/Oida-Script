<?php

namespace Tests\Parser;

use Exception;
use Oida\Parser\Literals\ParseString;


class StringParserTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_parse_string()
    {
        $tokens = $this->tokenize('"Hallo wie gehst"');
        $parser = new ParseString($tokens);


        $this->assertEquals('string', $parser->parse(0)[0]->getType());

    }
}