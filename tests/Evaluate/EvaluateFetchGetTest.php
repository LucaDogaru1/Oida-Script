<?php

namespace Tests\Evaluate;

use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateFetchGetTest extends ParserTestCase
{

    /**
     * @throws \Exception
     */
    public function test_fetch_get_method()
    {
        $link = '"https://jsonplaceholder.typicode.com/todos/1"';
        $inputClass = "
        heast x = holma($link);
        
       oida.sag(x.userId);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("1", $output);
    }

}