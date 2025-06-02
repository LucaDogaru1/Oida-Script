<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateArrayAccessTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_array_access()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        
        oida.sag(x[2]);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("3\n", $output);
    }
}