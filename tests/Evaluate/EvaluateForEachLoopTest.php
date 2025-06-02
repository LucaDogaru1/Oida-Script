<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateForEachLoopTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_foreach_loop_outputs_123()
    {
        $inputClass = "
        heast x = [1, 2, 3];
        
        fürAlles(x als y) {
        oida.sag(y);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("1\n2\n3\n", $output);
    }

}