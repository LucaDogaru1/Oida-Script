<?php

namespace Tests\Parser;

use Oida\Lexer\Lexer;
use PHPUnit\Framework\TestCase;

class ParserTestCase extends TestCase
{

    protected function tokenize(string $input): array
    {
        $lexer = new Lexer($input);
        return $lexer->tokenize();
    }

}
