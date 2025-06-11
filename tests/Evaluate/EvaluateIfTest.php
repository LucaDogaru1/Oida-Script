<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateIfTest extends ParserTestCase
{
    /**
     * @throws Exception
     */
    public function test_if_condition_x_größer_10_prints_yes()
    {
        $yes = '"yes"';
        $inputClass = "
        heast x = 12;
        wenn(x größer 10) {
        oida.sag($yes);
        }
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_if_condition_x_smaller_10_prints_yes()
    {
        $yes = '"yes"';
        $inputClass = "
        heast x = 9;
        wenn(x klana 10) {
        oida.sag($yes);
        }
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    public function test_if_condition_x_euqals_10_prints_yes()
    {
        $yes = '"yes"';
        $inputClass = "
        heast x = 10;
        wenn(x gleich 10) {
        oida.sag($yes);
        }
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_if_condition_x_not_equal_10_prints_yes()
    {
        $yes = '"yes"';
        $inputClass = "
        heast x = 12;
        wenn(x isned 10) {
        oida.sag($yes);
        }
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_if_condition_x_biggerOrSame_10_prints_yes()
    {
        $yes = '"yes"';
        $inputClass = "
        heast x = 10;
        wenn(x größerglei 10) {
        oida.sag($yes);
        }
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_if_condition_x_smallerOrSame_10_prints_yes()
    {
        $yes = '"yes"';
        $inputClass = "
        heast x = 10;
        wenn(x klanaglei 10) {
        oida.sag($yes);
        }
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_if_with_2_condition_x_between_5_and_10_prints_yes()
    {
        $yes = '"yes"';
        $inputClass = "
        heast x = 7;
        wenn(x klana 10 und x größer 5) {
        oida.sag($yes);
        }
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_if_with_condition_x_between_5_and_10_prints_yes_but_entering_the_elsif()
    {
        $no = '"no"';
        $yes = '"yes"';
        $inputClass = "
        heast x = 7;
        wenn(x klana 6) {
        oida.sag($no);
        } sonst wenn(x größer 6){
        oida.sag($yes);
        }
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_if_with_condition_x_between_5_and_10_prints_yes_but_entering_the_second_elsif()
    {
        $no = '"no"';
        $maybe = '"maybe"';
        $yes = '"yes"';
        $inputClass = "
        heast x = 7;
        wenn(x klana 3) {
        oida.sag($no);
        } sonst wenn(x klana 6){
        oida.sag($maybe);
        } sonst wenn(x größer 6){
        oida.sag($yes);
        }
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_if_with_condition_x_between_5_and_10_prints_yes_but_entering_else()
    {
        $no = '"no"';
        $maybe = '"maybe"';
        $yes = '"yes"';
        $inputClass = "
        heast x = 7;
        wenn(x klana 3) {
        oida.sag($no);
        } sonst wenn(x klana 6){
        oida.sag($maybe);
        } sonst wenn(x klana 7){
        oida.sag($maybe);
        } sonst{
         oida.sag($yes);
        }
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }
    /**
     * @throws Exception
     */
    public function test_if_condition_x_is_not_null()
    {
        $yes = '"yes"';
        $inputClass = "
        heast x = 12;
        wenn(x) {
        oida.sag($yes);
        }";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_if_condition_in_function_which_return_5()
    {
        $inputClass = "
        hawara hat(x) {
         wenn(x klana 10) {
        speicher x;
        }
        speicher 12;
        }
        heast x = hat(5);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $codeBlockNode->evaluate($env);

        $var = $env->getVariable("x");
        $this->assertEquals("5", $var);
    }


    /**
     * @throws Exception
     */
    public function test_if_condition_key_exist_in_assoArray_with_property_hat()
    {
        $yes = '"yes"';
        $name = '"name"';
        $inputClass = "
       heast x = [{{$name}: 0, 3: 5}];
       
       wenn(x[0].hat($name)) {
        oida.sag($yes);
       }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $codeBlockNode->evaluate($env);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_if_condition_key_exist_in_assoArray()
    {
        $yes = '"yes"';
        $name = '"name"';
        $inputClass = "
       heast x = [{{$name}: 1, 3: 5}];
       
       wenn(x[0].name) {
        oida.sag($yes);
       }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $codeBlockNode->evaluate($env);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_if_condition_key_exist_in_assoArray_on_class_instance()
    {
        $yes = '"yes"';
        $name = '"name"';
        $inputClass = "
        klasse hund {
        privat x = [{{$name}: 1, 3: 5}];
        
        öffentlich hawara sag() {
          wenn(this:x[0].name) {
        oida.sag($yes);
            }
        }
      }
      
      heast y = neu hund();
      
      y gibMa sag();
        
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $codeBlockNode->evaluate($env);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("yes\n", $output);
    }
}