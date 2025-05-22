<?php

namespace Tests\Parser;


use Exception;
use Oida\Parser\Print\ParsePrint;


class ParsePrintTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_parse_print()
    {
        $tokens = $this->tokenize("oida.sag(12);");
        $parser = new ParsePrint($tokens);

        $this->assertEquals('print', $parser->parse(0)[0]->getType());
    }

}