<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateForLoopTest extends ParserTestCase
{
    /**
     * @throws Exception
     */
    public function test_for_loop_incrementing_number_10_times()
    {
        $inputClass = "
        für(heast x = 0; x klana 10; x plusplus) {
        oida.sag(x);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('0123456789', $output);
    }

}