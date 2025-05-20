<?php

namespace Tests\Parser;

use Exception;
use Oida\AST\Class\ObjectNode;
use Oida\Environment\Environment;
use Oida\Parser\Class\ParseClass;
use Oida\Parser\ParseVariable;

class ParseVariableTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_variable_with_name_working()
    {
        $string = '"hello world"';
        $tokens = $this->tokenize("heast working = $string;");

        $parser = new ParseVariable($tokens);
        $name = $parser->parse(0)[0]->getName();

        $this->assertEquals('working', $name);
    }

    /**
     * @throws Exception
     */
    public function test_variable_with_value_of_string_named_hello_world()
    {
        $string = '"hello world"';
        $tokens = $this->tokenize("heast x = $string;");
        $parser = new ParseVariable($tokens);

        $value = $parser->parse(0)[0]->getValue()->getValue();

        $this->assertEquals('hello world', $value);
    }

    /**
     * @throws Exception
     */
    public function test_variable_with_value_of_number_12()
    {
        $tokens = $this->tokenize("heast x = 12;");
        $parser = new ParseVariable($tokens);

        $value = $parser->parse(0)[0]->getValue()->getValue();

        $this->assertEquals("12", $value);
    }


    /**
     * @throws Exception
     */
    public function test_create_variable_with_value_of_class_object()
    {
        $initializeToken = $this->tokenize('heast meinTest = neu User();');

        $initializeParser = new ParseVariable($initializeToken);

        $objectVariable = $initializeParser->parse(0)[0]->getValue();

        $this->assertInstanceOf(ObjectNode::class, $objectVariable);
    }
}