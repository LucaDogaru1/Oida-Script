<?php

namespace Tests\Evaluate\Database;

use Exception;
use Oida\Environment\Environment;
use Oida\Parser\ParseCodeBlock;
use Tests\Parser\ParserTestCase;

class EvaluateQueryTest extends ParserTestCase
{

    /**
     * @throws Exception
     */
    public function test_queries()
    {
        //Insert nicht eingefügt da der test immer failen würde weil sich die anzahl von den rows ändern würde nach jedem ausführen
        $insertQuery =' heast _ = conn->query("INSERT INTO users (name) VALUES (\'Max\');");';

        $max = '"Max"';
        $input = '
        heast conn = verbinde("database", "user", "secret", "db", 3306);
        heast _ = conn->query("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255));");
        heast rows = conn->query("SELECT * FROM users");
    ';

        $env = new Environment();
        $tokens = $this->tokenize($input);
        $codeBlock = new ParseCodeBlock($tokens);
        [$codeBlockNode, $index] = $codeBlock->parse(0);

        ob_start();
        $codeBlockNode->evaluate($env);
        ob_end_clean();

        $rows = $env->getVariable("rows");

        $this->assertIsArray($rows);
        $this->assertCount(8, $rows);
        $this->assertEquals("Max", $rows[0]["name"]);
    }
}