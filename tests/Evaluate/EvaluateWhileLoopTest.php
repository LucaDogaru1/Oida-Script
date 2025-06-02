<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateWhileLoopTest extends ParserTestCase
{
    /**
     * @throws Exception
     */
    public function test_while_loop_incrementing_number_10_times()
    {
        $inputClass = "
        heast x = 0;
        während(x klana 10 ){ 
        oida.sag(x); 
        x plusplus
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $expected = implode("\n", range(0, 9));
        $this->assertEquals($expected, trim($output));
    }
}