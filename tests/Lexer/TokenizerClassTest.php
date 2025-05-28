<?php

namespace Tests\Lexer;

use Tests\Parser\ParserTestCase;


class TokenizerClassTest extends ParserTestCase
{

    public function test_class_token()
    {
        $tokens = $this->tokenize("klasse");
        $token = $tokens[0];

        $this->assertEquals('T_CLASS', $token[0]);
    }

    public function test_class_property_access_token()
    {
        $tokens = $this->tokenize(":");
        $token = $tokens[0];

        $this->assertEquals('T_COLON', $token[0]);
    }

    public function test_class_new_token()
    {
        $tokens = $this->tokenize("neu");
        $token = $tokens[0];

        $this->assertEquals('T_NEW', $token[0]);
    }

    public function test_class_access_token()
    {
        $tokens = $this->tokenize("gibMa");
        $token = $tokens[0];

        $this->assertEquals('T_CLASS_ACCESS', $token[0]);
    }

    public function test_class_property_public_token()
    {
        $tokens = $this->tokenize("öffentlich");
        $token = $tokens[0];

        $this->assertEquals('T_CLASS_PROPERTY_PUBLIC', $token[0]);
    }

    public function test_class_property_private_token()
    {

        $tokens = $this->tokenize("privat");
        $token = $tokens[0];

        $this->assertEquals('T_CLASS_PROPERTY_PRIVATE', $token[0]);
    }

    public function test_class_this_keyword_token()
    {
        $tokens = $this->tokenize("this");
        $token = $tokens[0];

        $this->assertEquals('T_THIS', $token[0]);
    }

    public function test_class_method_token()
    {

        $tokens = $this->tokenize("hawara");
        $token = $tokens[0];

        $this->assertEquals('T_METHOD', $token[0]);
    }

    public function test_constructor_token()
    {
        $tokens = $this->tokenize("BauMeister");
        $token = $tokens[0];

        $this->assertEquals('T_CONSTRUCTOR', $token[0]);
    }

}