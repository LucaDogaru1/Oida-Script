<?php

namespace Tests\Evaluate;

use Exception;
use Oida\Environment\Environment;
use Oida\Lexer\Lexer;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateImportTest extends ParserTestCase
{
    public static function setUpBeforeClass(): void
    {
        putenv('OIDA_TEST_MODE=1');
    }

    /**
     * @throws Exception
     */
    public function test_import_und_methodenaufruf()
    {
        $name = '"Luca"';
        $className = '"/tmp/modul.oida"';

        file_put_contents('/tmp/modul.oida', <<<OIDA
klasse User {
    öffentlich hawara getName() {
        speicher $name;
    }
}
OIDA);

        file_put_contents('/tmp/main.oida', <<<OIDA
import $className;

heast u = neu User();
heast name = u gibMa getName();
OIDA);

        $code = file_get_contents('/tmp/main.oida');
        $lexer = new Lexer($code);
        $tokens = $lexer->tokenize();

        $parser = new ParseCodeBlock($tokens);
        [$node, $_] = $parser->parse(0);

        $env = new Environment();
        $node->evaluate($env);

        $this->assertEquals("Luca", $env->getVariable("name"));
    }

}