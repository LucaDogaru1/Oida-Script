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

        $this->assertEquals('0112', $output);
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

        $this->assertEquals('123', $output);
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

        $this->assertEquals('Max', $output);
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

        $this->assertEquals('10', $output);
    }


}