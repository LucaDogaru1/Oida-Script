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

        $this->assertEquals("4\n", $output);
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

        $this->assertEquals("5\n", $output);
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

        $this->assertEquals("3\n", $output);
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

        $this->assertEquals("1\n", $output);
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

        $this->assertEquals("4\n", $output);
    }


    /**
     * @throws Exception
     */
    public function test_property_access_mische()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        
        x.mische;
        
        fürAlles(x als y){
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

        $expected = implode("\n", range(0, 4));
        $this->assertNotEquals($expected, trim($output));
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

        $expected = implode("\n", $env->getVariable("new"));
        $this->assertEquals($expected, trim($output));
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

        $expected = implode("\n", $env->getVariable("new"));
        $this->assertEquals($expected, trim($output));
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

        $expected = implode("\n", $env->getVariable("new"));
        $this->assertEquals($expected, trim($output));
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

        $this->assertEquals("2\n", $output);
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

        $expected = implode("\n", $env->getVariable("new"));
        $this->assertEquals($expected, trim($output));
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

        $expected = implode("\n", [1, 3, 4]);
        $this->assertEquals($expected, trim($output));
    }

    /**
     * @throws Exception
     */
    public function test_property_access_gibRein()
    {
        $inputClass = "
        heast x = [1,2,3,4];
        x = x.gibRein(5);
       
        fürAlles(x als j) {
        oida.sag(j);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();
        $expected = implode("\n",$env->getVariable("x"));

        $this->assertEquals($expected, trim($output));
    }

    /**
     * @throws Exception
     */
    public function test_property_access_assoArray_gibRein()
    {
        $name = '"name"';
        $key = '"luca"';
        $inputClass = "
        heast x = [];
        x = x.gibRein({{$name}: {$key}});

        fürAlles(x als y ) {
        oida.sag(y.name);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("luca\n", $output);
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

        $expected = implode("\n", [1, 5, 3, 4]);
        $this->assertEquals($expected, trim($output));
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

        $expected = implode("\n", $env->getVariable("new"));
        $this->assertEquals($expected, trim($output));
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

        $this->assertEquals("1234\n", $output);
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

        $this->assertEquals("1-2-3-4\n", $output);
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

        $this->assertEquals("123\n", $output);
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

        $this->assertEquals("basst\n", $output);
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

        $this->assertEquals("2\n", $output);
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

        $this->assertEquals("basst\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_istArray()
    {

        $inputClass = "
        heast x = [1,2,3,4];
        oida.sag(x.istArray);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("basst\n", $output);
    }


    /**
     * @throws Exception
     */
    public function test_property_access_istAssoArray()
    {
        $inputClass = "
        heast x = {0: 1, 2 : 4};
        
        oida.sag(x.istAssoArray);
      
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("basst\n", $output);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_EXPLODIER_ohne_trennzeichen()
    {
        $word = '"HALLO WIE"';
        $inputClass = "
        heast x = $word;
        heast new = x.EXPLODIER;
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $codeBlockNode->evaluate($env);

        $this->assertCount(2, $env->getVariable("new"));
    }

    /**
     * @throws Exception
     */
    public function test_property_access_EXPLODIER_mit_trennzeichen()
    {
        $zeichen = '"-"';
        $word = '"HALLO-WIE"';
        $inputClass = "
        heast x = $word;
        heast new = x.EXPLODIER($zeichen);
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $codeBlockNode->evaluate($env);

        $this->assertCount(2, $env->getVariable("new"));
    }

    /**
     * @throws Exception
     */
    public function test_property_access_zuJson()
    {
        $inputClass = "
        heast x = {1: 0, 2: 1};
        heast new = x.zuJson;
        }
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        $codeBlockNode->evaluate($env);

        $var = $env->getVariable("new");
        $this->assertJson($var);
    }

    /**
     * @throws Exception
     */
    public function test_property_access_gibRein_aber_entfernt_key_wenn_schon_vorhanden()
    {
        $name = '"name"';
        $key = '"max"';
        $luca = '"luca"';
        $inputClass = "
        heast x = [{{$name}: $luca}];
        x = x.gibRein({{$name}: {$key}});

       oida.sag(x[0].name[0]);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals("luca\n", $output);

    }


}