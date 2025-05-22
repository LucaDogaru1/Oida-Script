<?php


namespace Tests\Parser;


use Exception;
use Oida\Parser\Literals\ParseIdentifier;

class ParseIdentifierTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_parse_identifier()
    {
        $tokens = $this->tokenize("dsdad");

        $parser = new ParseIdentifier($tokens);
        $type = $parser->parse(0)[0]->getType();

        $this->assertEquals('identifier',$type);

    }
}