<?php

namespace Oida\AST\Import;

use Exception;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;
use Oida\Lexer\Lexer;
use Oida\Parser\ParseCodeBlock;

class ImportNode extends ASTNode
{

    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): void
    {
        $fullPath = getenv('OIDA_TEST_MODE')
            ? $this->path
            : realpath(getcwd() . '/' . $this->path);

        if (!$fullPath || !file_exists($fullPath)) {
            throw new Exception("🛑 \033[1;4;97mImport-Datei nicht gefunden:\033[0m \033[1;4;97m{$this->path}\033[0m");
        }
        $code = file_get_contents($fullPath);
        $lexer = new Lexer($code);
        $tokens = $lexer->tokenize();
        [$node, $_] = (new ParseCodeBlock($tokens))->parse(0);
        $node->evaluate($env);
    }
}