<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateTest extends ParserTestCase
{
    /**
     * @throws Exception
     */
    public function test_case_add()
    {
        $inputClass = "
        test addiere() {
        schau(2 plus 3 gleich 5 );
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $actual = trim($output);

        $this->assertSame(48, strlen($actual));

    }

    /**
     * @throws Exception
     */
    public function test_string_vergleich()
    {
        $string1 = '"hallo"';
        $string2 = '"hallo"';

        $inputClass = "
        test vergleichS() {
        schau($string1 gleich $string2);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $actual = trim($output);

        $this->assertSame(51, strlen($actual));
    }

    /**
     * @throws Exception
     */
    public function test_array_value_vergleich()
    {

        $inputClass = "
        test vergleichA() {
        heast x = [1,2,3];
        schau(x gleich [1,2,3]);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $actual = trim($output);

        $this->assertSame(51, strlen($actual));
    }

}