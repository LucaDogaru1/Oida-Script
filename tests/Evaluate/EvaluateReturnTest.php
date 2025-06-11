<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateReturnTest extends ParserTestCase
{
    /**
     * @throws Exception
     */
    public function test_return_asso_array()
    {
        $name = '"kiko"';
        $assoName = '"name"';
        $assoAlter = '"alter"';
        $assoGewicht = '"gewicht"';

        $inputClass = "
        klasse katze {
        
        privat name;
        privat alter;
        privat gewicht;
        
        BauMeister(name, alter, gewicht){
        this:name = name;
        this:alter = alter;
        this:gewicht = gewicht;
        }
        
        
          öffentlich hawara ja() {
       speicher {{$assoName}: this:name, {$assoAlter}: this:alter, {$assoGewicht}: this:gewicht};
       }
        }
      
       heast my = neu katze($name, 1, 2);
       heast x = my gibMa ja();
        oida.sag(x.name);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("kiko\n", $output);
    }
}