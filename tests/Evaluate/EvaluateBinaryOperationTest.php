<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateBinaryOperationTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_add_2_and_2_sum_4()
    {
        $inputClass = "
        oida.sag(2 plus 2 );
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('4',$output );
    }

    public function test_2_plus_2_mal_5_plus_10_durch_10_prio_soll_beachtet_werden_und_ergebnis_13()
    {
        $inputClass = "
        oida.sag(2 plus 2 mal 5 plus 10 durch 10 );
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('13',$output );
    }

    /**
     * @throws Exception
     */
    public function test_store_binary_expression_in_variable()
    {
        $inputClass = "
        heast x = 2 plus 2 mal 3;
        oida.sag(x);
        ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('8',$output );
    }
}