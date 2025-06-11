<?php

namespace Oida\Interpreter;

use Exception;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\Environment\Environment;
use Oida\Exceptions\FehlerException;
use Oida\Lexer\Lexer;
use Oida\Parser\ParseCodeBlock;

class Interpreter
{
    private CodeBlockNode $ast;
    private Environment $env;
    private string $fileContent;


    public function __construct(string $fileContent, Environment $env)
    {
        $this->fileContent = $fileContent;
        $this->env = $env;
    }

    /**
     * @throws Exception
     */
    public function evaluate(): void
    {
        $this->prepareForEvaluation();

        try {
            $this->ast->evaluate($this->env);
        } catch (FehlerException $e) {
            echo "\033[1;31m\033[1mFEHLER GEWORFEN:\033[0m \033[1;31m⚠️ \033[1;97m" . $e->getMessage() . "\033[0m\n";
        }
        catch (Exception $e) {
            echo "🛑 Fehler im Interpreter: " . $e->getMessage() . "\n";
        }
    }

    /**
     * @throws Exception
     */
    private function prepareForEvaluation(): void
    {
        $lexer = new Lexer($this->fileContent);
        $tokens = $lexer->tokenize();

        [$node, $_] = (new ParseCodeBlock($tokens, $this->env))->parse(0);
        $this->ast = $node;
    }

}