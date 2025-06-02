<?php

namespace Tests\Parser\Class;

use Exception;
use Oida\AST\Class\ConstructorNode;
use Oida\Parser\Class\ParseClass;
use Tests\Parser\ParserTestCase;

class ParseClassTest extends ParserTestCase
{
    /**
     * @throws Exception
     */
    public function test_class_variable_named_test_with_value_of_integer()
    {
        $tokens = $this->tokenize(

            "klasse yo {
öffentlich na = 2;
}"

        );

        $parseClass = new ParseClass($tokens);

        $variable = [];

        foreach ($parseClass->parse(0)[0]->getProperties() as $property) {
            $variable[$property->getName()] = $property->getValue()->getValue();
        }

        $this->assertEquals(["na" => 2], $variable);
    }

    /**
     * @throws Exception
     */
    public function test_class_variable_named_test_with_value_of_string()
    {
        $string = '"hello world"';
        $tokens = $this->tokenize(

            "klasse yo {
öffentlich stringVariable = $string;
}"

        );

        $parseClass = new ParseClass($tokens);

        $variable = [];

        foreach ($parseClass->parse(0)[0]->getProperties() as $property) {
            $variable[$property->getName()] = $property->getValue()->getValue();
        }

        $this->assertEquals(["stringVariable" => "hello world"], $variable);
    }

    /**
     * @throws Exception
     */
    public function test_private_method_called_meMethod_in_class()
    {

        $tokens = $this->tokenize("klasse ich
        {
        privat hawara meMethod() {}
        }");

        $parseClass = new ParseClass($tokens);
        $methodName = "";

        foreach ($parseClass->parse(0)[0]->getMethods() as $method) {
            $methodName = $method->getMethodName();
        }

        $this->assertEquals("meMethod", $methodName);

    }


    /**
     * @throws Exception
     */
    public function test_define_multiple_private_methods_in_class()
    {

        $tokens = $this->tokenize("klasse ich
        {
        privat hawara meMethod() {}
        privat hawara meMethodTwo() {}
        }");

        $parseClass = new ParseClass($tokens);
        $methodName = [];

        foreach ($parseClass->parse(0)[0]->getMethods() as $method) {
            $methodName[] = $method->getMethodName();
        }

        $this->assertEquals(["meMethod", "meMethodTwo"], $methodName);
    }


    /**
     * @throws Exception
     */
    public function test_define_two_methods_in_class_one_private_one_public()
    {

        $tokens = $this->tokenize("klasse ich
        {
        privat hawara meMethod() {}
        
        öffentlich hawara meMethodTwo() {}
        }");

        $parseClass = new ParseClass($tokens);
        $visibility = [];

        foreach ($parseClass->parse(0)[0]->getMethods() as $method) {
            $visibility[] = $method->getVisibility();
        }

        $this->assertEquals(["privat", "öffentlich"], $visibility);
    }

    /**
     * @throws Exception
     */
    public function test_multiple_methods_and_multiple_properties_with_different_visibility_in_class()
    {

        $string = '"hello world"';
        $tokens = $this->tokenize("klasse ich
        {
        privat variableOne = 2;
        öffentlich variableTwo = $string;
        
        privat hawara meMethod() {}
        
        öffentlich hawara meMethodTwo() {}
      }"
        );
        $parseClass = new ParseClass($tokens);

        $methods = [];
        $variable = [];
        foreach ($parseClass->parse(0)[0]->getMethods() as $method) {
            $methods[] = $method->getMethodName();
        }

        foreach ($parseClass->parse(0)[0]->getProperties() as $property) {
            $variable[$property->getName()] = $property->getValue()->getValue();
        }

        $this->assertEquals(['variableOne' => 2, 'variableTwo' => 'hello world'], $variable);
        $this->assertEquals(["meMethod", "meMethodTwo"], $methods);
    }


    /**
     * @throws Exception
     */
    public function test_initialize_constructor()
    {

        $tokens = $this->tokenize("klasse testKlasse
        {
             BauMeister(){}
        }"
        );
        $parseClass = new ParseClass($tokens);

        $constructor = $parseClass->parse(0)[0]->getConstructor();


        $this->assertInstanceOf( ConstructorNode::class, $constructor);
    }


    /**
     * @throws Exception
     */
    public function test_having_properties_methods_and_constructor_in_one_class()
    {
        $string = '"hello world"';
        $tokens = $this->tokenize("klasse ich
        {
        privat variableOne = 2;
        öffentlich variableTwo = $string;
        
        BauMeister(){}
        
        privat hawara meMethod() {}
        
        öffentlich hawara meMethodTwo() {}
      }"
        );
        $parseClass = new ParseClass($tokens);
        $methodNames = [];
        $variable = [];

        foreach ($parseClass->parse(0)[0]->getMethods() as $method) {
            $methodNames[] = $method->getMethodName();
        }

        foreach ($parseClass->parse(0)[0]->getProperties() as $property) {
            $variable[$property->getName()] = $property->getValue()->getValue();
        }

        $this->assertEquals(['variableOne' => 2, 'variableTwo' => 'hello world'], $variable);
        $this->assertEquals(["meMethod", "meMethodTwo"], $methodNames);
    }

}
