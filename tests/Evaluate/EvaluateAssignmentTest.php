<?php

namespace Tests\Evaluate;

use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateAssignmentTest extends ParserTestCase
{
    /**
     * @throws \Exception
     */
    public function test_reassign_variable()
    {
        $inputClass = "
        heast x = 2;
        x = 1;
        oida.sag(x);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('1', $output);
    }
}