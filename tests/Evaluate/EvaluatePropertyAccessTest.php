<?php

namespace Tests\Evaluate;

use Exception;
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

    /**
     * @throws Exception
     */
    public function test_property_access_has_value()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        
        wenn(x.hat(3)){
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

    /**
     * @throws Exception
     */
    public function test_property_access_erstesElemetn()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        
        oida.sag(x.erstesElement);
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

    /**
     * @throws Exception
     */
    public function test_property_access_letztesElement()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        
        heast y = x.letztesElement;
        
        oida.sag(y);
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


    /**
     * @throws Exception
     */
    public function test_property_access_mische()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        
        heast new = x.mische;
        
        fürAlles(new als y){
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

        $this->assertNotEquals('1234', $output);
    }


    /**
     * @throws Exception
     */
    public function test_property_access_ohneDuplikat()
    {
        $inputClass = "
        heast x = [1,2,2,3,4];

        heast new = x.ohneDuplikat;

        fürAlles(new als y){
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

        $this->assertEquals('1234', $output);
    }


    /**
     * @throws Exception
     */
    public function test_property_access_sortiere()
    {
        $inputClass = "
        heast x = [3,1,4,2];

        heast new = x.sortiere;

        fürAlles(new als y){
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

        $this->assertEquals('1234', $output);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_sortiere_absteigend()
    {
        $inputClass = "
        heast x = [3,1,4,2];

        heast new = x.sortiereAbsteigend;

        fürAlles(new als y){
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

        $this->assertEquals('4321', $output);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_indexVon()
    {
        $inputClass = "
        heast x = [3,1,4,2];

           oida.sag(x.indexVon(4));
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('2', $output);
    }


    /**
     * @throws Exception
     */
    public function test_property_access_flach()
    {
        $inputClass = "
        heast x = [1, [2,3], [4,5], 6];
        heast new = x.flach;

        fürAlles(new als y) {
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

        $this->assertEquals('123456', $output);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_entferne()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        heast new = x.entferne(2);

        fürAlles(new als y) {
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

        $this->assertEquals('134', $output);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_gibRein()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        heast new = x.gibRein(5);

        fürAlles(new als y) {
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

        $this->assertEquals('12345', $output);
    }


    /**
     * @throws Exception
     */
    public function test_property_access_ersetze()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        heast new = x.ersetz([2,5]);

        fürAlles(new als y) {
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

        $this->assertEquals('1534', $output);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_kombinier()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        heast y = [5,6,7];
        heast new = x.kombinier(y);

        fürAlles(new als y) {
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

        $this->assertEquals('1234567', $output);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_zuText_ohne_trenn_zeichen()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        heast new = x.zuText;

       oida.sag(new);
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

    /**
     * @throws Exception
     */
    public function test_property_access_zuText_mit_trenn_zeichen()
    {

        $trennZeichen = '"-"';
        $inputClass = "
        heast x = [1,2,3,4];
        heast new = x.zuText($trennZeichen);

        oida.sag(new);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('1-2-3-4', $output);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_zuText_auf_zahl()
    {
        $inputClass = "
        heast x = 123;
        heast new = x.zuText;

       oida.sag(new);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('123', $output);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_istZahl()
    {
        $inputClass = "
        heast x = 123;

       oida.sag(x.istZahl);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('', $output);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_ist_keine_zahl()
    {
        $string = '"da"';
        $inputClass = "
        heast x = $string;
        
        wenn(!x.istZahl) {
        oida.sag(2);
        } 
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('2', $output);
    }



    /**
     * @throws Exception
     */
    public function test_property_access_textHat()
    {

        $string = '"Hallo welt"';
        $welt = '"welt"';
        $inputClass = "
        heast x = $string;
        oida.sag(x.textHat($welt));
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('', $output);
    }

}