<?php

namespace Tests\Evaluate;

use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluatePropertyAccessTest extends ParserTestCase
{
    public function test_property_access_length()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        
        oida.sag(x.anzahl);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('4', $output);
    }

    public function test_property_access_empty()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        
        wenn(x.leer){
        oida.sag(3);
        } sonst{
        oida.sag(5);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('5', $output);
    }

    public function test_property_access_has_value()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        
        wenn(x.hatWert(3)){
        oida.sag(3);
        } sonst{
        oida.sag(5);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('3', $output);
    }

}