<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\Print\ParsePrint;
use Tests\Parser\ParserTestCase;

class EvaluatePrintTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_evaluate_print_with_number()
    {
        $tokens = $this->tokenize("oida.sag(12);");

        $parser = new ParsePrint($tokens);
        $parsedResult = $parser->parse(0);

        $printNode = $parsedResult[0];

        $env = new Environment();
        $evaluatedValue = $printNode->evaluate($env);

        $this->assertNotNull($parsedResult);
        $this->assertEquals('12', $evaluatedValue);
    }

    /**
     * @throws Exception
     */
    public function test_evaluate_print_with_string()
    {
        $string = '"Hallo"';
        $tokens = $this->tokenize("oida.sag($string);");

        $parser = new ParsePrint($tokens);
        $parsedResult = $parser->parse(0);

        $printNode = $parsedResult[0];

        $env = new Environment();
        $evaluatedValue = $printNode->evaluate($env);


        $this->assertNotNull($parsedResult);
        $this->assertEquals('Hallo', $evaluatedValue);
    }

    /**
     * @throws Exception
     */
    public function test_evaluate_print_with_string_and_number()
    {
        $string = '"Hallo"';
        $emptyString = '" "';
        $tokens = $this->tokenize( "oida.sag($string, $emptyString, 12);");


        $parser = new ParsePrint($tokens);
        $parsedResult = $parser->parse(0);

        $printNode = $parsedResult[0];

        $env = new Environment();
        $evaluatedValue = $printNode->evaluate($env);

        $this->assertCount(3, $printNode->getValues());
        $this->assertEquals("Hallo 12", $evaluatedValue);
    }

}