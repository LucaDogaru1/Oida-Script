#!/usr/bin/env php
<?php

require_once __DIR__ . "/vendor/autoload.php";

use Oida\AST\Test\TestNode;
use Oida\Environment\Environment;
use Oida\Interpreter\Interpreter;

set_exception_handler(function ($e) {
    fwrite(STDERR, "Fatal error: Uncaught Exception: " . $e->getMessage() . "\n");
    exit(1);
});

$fileName = $_SERVER['argv'][1];

if (str_ends_with($fileName, '.oida')) {
    $fileContent = file_get_contents($fileName);
    $env = new Environment();
    $interpreter = new Interpreter($fileContent, $env);

    $interpreter->evaluate();
    if (TestNode::$executedTests > 0) {
        TestNode::summary();
    }
} else {
    echo "Error: The file must have the '.oida' extension.\n";
}
