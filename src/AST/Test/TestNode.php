<?php

namespace Oida\AST\Test;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\Environment\Environment;
use Throwable;

class TestNode extends ASTNode
{
    private string $name;
    private array $args;
    private CodeBlockNode $body;

    public static int $passed = 0;
    public static int $failed = 0;
    public static int $assertions = 0;

    public static int $executedTests = 0;



    public function __construct(string $name, array $args, CodeBlockNode $body)
    {
        $this->name = $name;
        $this->args = $args;
        $this->body = $body;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        $red = "\e[31m";
        $white = "\e[97m";
        $reset = "\e[0m";

        echo "🔬 {$white}Test '{$this->name}' wird ausgeführt...{$reset}\n";
        $env->enterTestContext();
        try {
            foreach ($this->body->getStatements() as $stmt) {
                $stmt->evaluate($env);
            }
            self::$passed++;
        } catch (Throwable $e) {
            self::$failed++;
            echo "{$red}❌ Test '{$this->name}' fehlgeschlagen: {$e->getMessage()}{$reset}\n";
        } finally {
            $env->leaveTestContext();
        }

        self::$executedTests++;
    }

    public static function addAssertion(): void
    {
        self::$assertions++;
    }

    public static function summary(): void
    {
        $reset = "\e[0m";
        $white = "\e[97m";
        $bgGreen = "\e[42m";
        $bgRed = "\e[41m";

        if (self::$failed > 0) {
            echo "\n{$bgRed}{$white} ❌ Tests fehlgeschlagen: "
                . self::$failed . ", OK: " . self::$passed
                . ", Assertions: " . self::$assertions
                . " {$reset}\n";
        } else {
            echo "\n{$bgGreen}{$white} ✅ OK ("
                . self::$passed . " tests, "
                . self::$assertions . " assertions)"
                . " {$reset}\n";
        }
    }
}