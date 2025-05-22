<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateFunctionTest extends ParserTestCase
{

    /**
     * @throws \Exception
     */
    public function test_void_function_displaying_6()
    {
        $inputClass = "
        hawara sag() {
        oida.sag(6);
        }
        
        sag();
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('6', $output);
    }

    /**
     * @throws \Exception
     */
    public function test_void_function_displaying_6_with_parameter()
    {
        $inputClass = "
        hawara sag(a) {
        oida.sag(a);
        }
        
        sag(6);
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('6', $output);
    }

    public function test_void_function_displaying_6_and_7_with_parameter()
    {
        $inputClass = "
        hawara sag(a, b) {
        oida.sag(a, b);
        }
        
        sag(6, 7);
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('67', $output);
    }

    /**
     * @throws Exception
     */
    public function test_return_function_with_value_8_and_console_log_it()
    {
        $inputClass = "
        hawara sag(a) {
         speicher a;
        }
        
        heast x = sag(8);;
        oida.sag(x);
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('8', $output);
    }

    public function test_void_function_stored_in_variable_should_throw_exception()
    {
        $inputClass = "
        hawara sag(a) {
          oida.sag(a);
        }
        
        heast x = sag(8);;
        ";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("🛑 \033[1;31mHÄ??,\033[0m \033[1;31mdu kannst nicht ");

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $codeBlockNode->evaluate($env);

    }
}