<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateFilterTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_filter_return_new_array()
    {
        $inputClass = "
        heast x = [1,2,3,4,5];
        heast neuesArray = nimmAusse => x(number klana 4);
        
        fürAlles(neuesArray als array) { oida.sag(array);}
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
}