<?php

namespace Tests\Evaluate\Database;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateConnectionTest extends ParserTestCase
{
    /**
     * @throws Exception
     */
    public function test_db_connect()
    {
        $host = '"database"';
        $user = '"user"';
        $password = '"secret"';
        $db = '"db"';
        $port = 3306;

        $inputClass = "
            heast x = verbinde($host, $user, $password, $db, $port);
       ";

        $env = new Environment();
        $tokens = $this->tokenize($inputClass);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $currentIndex] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        $output = ob_get_clean();

        $this->assertEquals('Connection ist eingerichtet' . " \n", $output);
    }
}