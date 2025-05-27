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

    /**
     * @throws Exception
     */
    public function test_for_loop_loops_over_array()
    {
        $inputClass = "
        heast y = [1,2,3,4];
        für(heast x = 0; x klana y.anzahl; x plusplus) {
        oida.sag(y[x]);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('1234', $output);
    }

}