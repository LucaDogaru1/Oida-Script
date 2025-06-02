<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateMapTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_map_return_new_array()
    {
        $inputClass = "
        heast array = [1,2,3,4,5];
        heast neuesArray = karte => array(number => number mal 2);
        
        fürAlles(neuesArray als item) { oida.sag(item);}
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("2\n4\n6\n8\n10\n", $output);
    }
}