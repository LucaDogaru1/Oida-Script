<?php

namespace Tests\Evaluate;

use Exception;
use Oida\AST\Class\ClassNode;
use Oida\AST\Class\MethodNode;
use Oida\AST\Class\ObjectNode;
use Oida\Environment\ClassInstance;
use Oida\Environment\Environment;
use Oida\Parser\Class\ParseClass;
use Oida\Parser\Class\ParseClassMethod;
use Oida\Parser\Class\ParseInitializeObject;
use Oida\Parser\Class\ParseMethodCall;
use Oida\Parser\ParseCodeBlock;
use Oida\Parser\ParseVariable;
use Tests\Parser\ParserTestCase;

class EvaluateObjectTest extends ParserTestCase
{
    /**
     * @throws Exception
     */
    public function test_creating_class_and_initialize_object_of_this_class()
    {
        $inputClass = "klasse User{}";
        $inputInitialize = "neu User()";
        $env = new Environment();

        $classTokens = $this->tokenize($inputClass);
        $initializeTokens = $this->tokenize($inputInitialize);

        $classParser = new ParseClass($classTokens);
        [$classNode, $classIndex] = $classParser->parse(0);

        $initializeParser = new ParseInitializeObject($initializeTokens);
        [$objectNode, $objectIndex] = $initializeParser->parse(0);

        $classNode->evaluate($env);
        $objectNode->evaluate($env);

        $this->assertInstanceOf(ObjectNode::class, $objectNode);
        $this->assertEquals('User', $objectNode->getObjectName());
        $this->assertInstanceOf(ClassNode::class, $classNode);
        $this->assertEquals('User', $classNode->getClassName());
        $this->assertEquals('User', $classNode->getClassName());
    }

    /**
     * @throws Exception
     */
    public function test_creating_class_with_methods_and_initialize_object_of_this_class()
    {
        $inputClass = "klasse Desktop{
        öffentlich hawara hallo() {oida.sag(12);}
        }";
        $inputInitialize = "heast object = neu Desktop();";
        $env = new Environment();

        $classTokens = $this->tokenize($inputClass);
        $initializeTokens = $this->tokenize($inputInitialize);

        $classParser = new ParseClass($classTokens);
        [$classNode, $classIndex] = $classParser->parse(0);

        $initializeParser = new ParseVariable($initializeTokens);
        [$objectNode, $objectIndex] = $initializeParser->parse(0);

        $classNode->evaluate($env);
        $objectNode->evaluate($env);

        $this->assertInstanceOf(ObjectNode::class, $objectNode->getValue());
        $this->assertInstanceOf(ClassNode::class, $classNode);

    }

    /**
     * @throws Exception
     */
    public
    function test_creating_class_with_method_and_property_and_initialize_object_of_this_class()
    {
        $inputClass = "klasse Animal{
        privat flieg = 0;
        
        öffentlich hawara sag() {oida.sag(12);}
        }";
        $inputInitialize = "neu Animal();";
        $env = new Environment();

        $classTokens = $this->tokenize($inputClass);
        $initializeTokens = $this->tokenize($inputInitialize);

        $classParser = new ParseClass($classTokens);
        [$classNode, $classIndex] = $classParser->parse(0);

        $initializeParser = new ParseInitializeObject($initializeTokens);
        [$objectNode, $objectIndex] = $initializeParser->parse(0);

        $classNode->evaluate($env);
        $objectNode->evaluate($env);

        $this->assertCount(1, $objectNode->getMethods($env));
        $this->assertCount(1, $objectNode->getProperties($env));
    }

    /**
     * @throws Exception
     */
    function test_check_if_variable_value_is_instance_of_ObjectNode()
    {
        $classInitialize = "klasse Tiger{}";
        $inputInitialize = "heast x = neu Tiger();";
        $initializeTokens = $this->tokenize($inputInitialize);
        $initializeClass = $this->tokenize($classInitialize);
        $env = new Environment();


        $initializeClass = new ParseClass($initializeClass);
        [$classNode, $classIndex] = $initializeClass->parse(0);

        $variableParser = new ParseVariable($initializeTokens);
        [$variableNod, $variableIndex] = $variableParser->parse(0);


        $classNode->evaluate($env);
        $variableNod->evaluate($env);

        $this->assertInstanceOf(ObjectNode::class, $variableNod->getValue());
    }


    /**
     * @throws Exception
     */
    function test_creating_class_with_method_then_initialize_variable_with_object_then_calling_method_named_JASICHA_with_console_output_12()
    {
        $inputClass = "klasse Cat{
        öffentlich hawara JASICHA() {oida.sag(12);}
        }
        heast x = neu Cat();
        x gibMa JASICHA();";
        $env = new Environment();

        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $this->assertEquals("12", $codeBlockNode->evaluate($env));
    }

    /**
     * @throws Exception
     */
    function test_creating_class_with_method_then_initialize_variable_with_object_then_calling_method_named_NASICHA_with_one_argument_and_the_output_of_14()
    {
        $inputClass = "klasse Dog{
        öffentlich hawara JASICHA(y) {oida.sag(y);}
        }
        heast joa = neu Dog();
        heast my = 14;
        joa gibMa JASICHA(my);";
        $env = new Environment();

        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $this->assertEquals("14", $codeBlockNode->evaluate($env));
    }


    /**
     * @throws Exception
     */
    public function test_class_with_class_variable_and_printing_instance_variable_on_method_call()
    {
        $inputClass = "klasse OPA{
        privat test = 3;
        
        öffentlich hawara KOMM() {
        oida.sag(this:test);
         }
      }
        heast joa = neu OPA();
        joa gibMa KOMM();";
        $env = new Environment();

        $tokens = $this->tokenize($inputClass);

        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $this->assertEquals("3", $codeBlockNode->evaluate($env));
    }

    /**
     * @throws Exception
     */
    public function test_class_with_constructor_and_printing_instance_variable_on_method_call_after_parsing_value_to_constructor()
    {
        $inputClass = "klasse OMA{
        privat testConstructor = 0;
        
        BauMeister(testConstructor) {
        this:testConstructor = testConstructor;
        }
        
        öffentlich hawara HUHU() {
        oida.sag(this:testConstructor);
         }
      }
        heast nah = neu OMA(6);
        nah gibMa HUHU();";
        $env = new Environment();

        $tokens = $this->tokenize($inputClass);

        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $this->assertEquals("6", $codeBlockNode->evaluate($env));
    }


    /**
     * @throws Exception
     */
    public function test_class_with_constructor_and_2_different_instance_and_2_different_value_output()
    {
        $inputClass = "klasse HAI{
        privat testConstructor = 0;
        
        BauMeister(testConstructor) {
        this:testConstructor = testConstructor;
        }
        
        öffentlich hawara SICHA() {
        oida.sag(this:testConstructor);
         }
      }
        heast nah = neu HAI(6);
        heast jo = neu HAI(5);
        
        nah gibMa SICHA();
        jo gibMa SICHA();";
        $env = new Environment();

        $tokens = $this->tokenize($inputClass);

        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();

        $codeBlockNode->evaluate($env);

        $output = ob_get_clean();

        $this->assertEquals("65", $output);
    }

    /**
     * @throws Exception
     */
    public function test_class_car_with_2_instance_with_3_values_and_every_instance_different_values()
    {
        $marke = '""';
        $bmw = '"bwm"';
        $audi = '"audi"';
        $inputClass = "klasse Car{
        privat marke = $marke;
        privat modell = 0;
        privat kmh = 0;
        
        BauMeister(marke, modell, kmh) {
        this:marke = marke;
        this:modell = modell;
        this:kmh = kmh;
        }
        
        öffentlich hawara getInfo(){
        oida.sag(this:marke, this:modell, this:kmh);
         }
         
      }
        heast bmw = neu Car($bmw, 3, 210);
        heast audi = neu Car($audi, 1, 300);
        
        bmw gibMa getInfo();
        audi gibMa getInfo();";

        $env = new Environment();

        $tokens = $this->tokenize($inputClass);

        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();

        $codeBlockNode->evaluate($env);

        $output = ob_get_clean();

        var_dump($output);
        $this->assertEquals("bwm3210audi1300", $output);
    }


    /**
     * @throws Exception
     */
    public function test_methode_die_privat_ist_darf_nicht_aufgerufen_werden_außerhalb_der_klasse_und_wirft_eine_Exception()
    {
        $inputClass = "klasse schuh{
  
        privat hawara SICHA() {
        oida.sag(5);
         }
      }
      
        heast jo = neu schuh();
        jo gibMa SICHA();";

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Oida, was glaubst eigentlich");

        $env = new Environment();

        $tokens = $this->tokenize($inputClass);

        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $codeBlockNode->evaluate($env);
    }


    /**
     * @throws Exception
     */
    public function test_private_methode_wird_in_öffentlicher_methode_aufgerufen_und_außerhalb_der_klasse_angezeigt()
    {
        $inputClass = "klasse schuh{
  
        privat hawara SICHA(a) {
        oida.sag(a);
         }
         
         öffentlich hawara HHH(){
         this:SICHA(5);
         }
      }
      
        heast jo = neu schuh();
        jo gibMa HHH();";

        $env = new Environment();

        $tokens = $this->tokenize($inputClass);

        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $this->assertEquals('5', $codeBlockNode->evaluate($env));
    }


    /**
     * @throws Exception
     */
    public function test_class_variable_with_no_initialization()
    {

        $inputClass = "klasse kuh{
         privat a;
       
         öffentlich hawara HHH(){
         this:a = 5;
         oida.sag(this:a);
         }
      }
      
        heast jo = neu kuh();
        jo gibMa HHH();";

        $env = new Environment();

        $tokens = $this->tokenize($inputClass);

        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $this->assertEquals('5', $codeBlockNode->evaluate($env));
    }

}
