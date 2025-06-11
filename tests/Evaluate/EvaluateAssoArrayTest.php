<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateAssoArrayTest extends ParserTestCase
{
    /**
     * @throws Exception
     */
    public function test_foreach_loop_with_asso_array()
    {
        $inputClass = "
        heast x = {0: 1, 1: 2};
        
        fürAlles(x als key => value) {
        oida.sag(key);
        oida.sag(value);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("0\n1\n1\n2\n", $output);
    }

    public function test_foreach_loop_with_asso_array_with_value_array()
    {
        $inputClass = "
        heast x = {0: [1,2,3]};
        
        fürAlles(x als key => value) {
        oida.sag(value.zuText);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("123\n", $output);
    }



    /**
     * @throws Exception
     */
    public function test_asso_array_access_to_value_over_key()
    {

        $name = '"name"';
        $max = '"Max"';
        $inputClass = "
        heast x = { $name : $max };
        
        oida.sag(x.name);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("Max\n", $output);
    }


    /**
     * @throws Exception
     */
    public function test_foreach_loop_with_asso_array_key_0_which_holds_asso_array_as_key_and_value()
    {
        $inputClass = "
        heast x = {0: {1: 0}};
        
        fürAlles(x.0 als key => value) {
            oida.sag(key);
             oida.sag(value);
         }
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("1\n0\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_return_assoArray_in_function()
    {
        $inputClass = "
       hawara ja() {
       speicher {0: 10, 1: 5};
       }
       heast x = ja();
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $codeBlockNode->evaluate($env);

        $this->assertIsArray($env->getVariable("x"));
    }

//    /**
//     * @throws Exception
//     */
//    public function test_push_value_into_assoArray_key()
//    {
//        $name = '"name"';
//        $luca = '"luca"';
//        $inputClass = "
//        heast x = [{{$name}: []}];
//        x[0].name.gibRein($luca);
//
//       ";
//
//        $env = new Environment();
//        $tokens = $this->tokenize($inputClass);
//        $codeBlock = new ParseCodeBlock($tokens);
//        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);
//
//
//        $codeBlockNode->evaluate($env);
//
//        $var  = $env->getVariable("x");
//
//    }


}