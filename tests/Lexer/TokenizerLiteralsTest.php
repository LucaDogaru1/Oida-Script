<?php

namespace Lexer;

use Oida\Lexer\Lexer;
use PHPUnit\Framework\TestCase;

class TokenizerLiteralsTest extends TestCase
{
    public function test_tokenizer_for_string()
    {
        $input = '"string"';
        $lexer = new Lexer($input);

        $tokens = $lexer->tokenize();
        $token = $tokens[0];

        $this->assertEquals('T_STRING', $token[0]);
        $this->assertEquals("string", $token[1]);
    }

    public function test_tokenizer_for_number()
    {
        $input = 12;
        $lexer = new Lexer($input);

        $tokens = $lexer->tokenize();
        $token = $tokens[0];

        $this->assertEquals('T_NUMBER', $token[0]);
        $this->assertEquals(12, $token[1]);
    }

    public function test_tokenizer_for_Boolean_true()
    {
        $input = "basst";
        $lexer = new Lexer($input);

        $tokens = $lexer->tokenize();
        $token = $tokens[0];

        $this->assertEquals('T_TRUE', $token[0]);
    }

    public function test_tokenizer_for_Boolean_false()
    {
        $input = "sichaned";
        $lexer = new Lexer($input);

        $tokens = $lexer->tokenize();
        $token = $tokens[0];

        $this->assertEquals('T_FALSE', $token[0]);
    }

}