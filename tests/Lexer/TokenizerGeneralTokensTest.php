<?php

namespace Lexer;

use Oida\Lexer\Lexer;
use PHPUnit\Framework\TestCase;

class TokenizerGeneralTokensTest extends TestCase
{
    public function test_print_token()
    {
        $input = "oida.sag";
        $lexer = new Lexer($input);

        $tokens = $lexer->tokenize();
        $token = $tokens[0];

        $this->assertEquals('T_PRINT', $token[0]);
    }

    public function test_return_token()
    {
        $input = "speicher";
        $lexer = new Lexer($input);

        $tokens = $lexer->tokenize();
        $token = $tokens[0];

        $this->assertEquals('T_RETURN', $token[0]);
    }

    public function test_let_token()
    {
        $input = "heast";
        $lexer = new Lexer($input);

        $tokens = $lexer->tokenize();
        $token = $tokens[0];

        $this->assertEquals('T_LET', $token[0]);
    }

    public function test_identifier_token()
    {
        $input = "hallo";
        $lexer = new Lexer($input);

        $tokens = $lexer->tokenize();
        $token = $tokens[0];

        $this->assertEquals('T_IDENTIFIER', $token[0]);
    }

}