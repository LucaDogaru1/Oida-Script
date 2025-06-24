<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Oida\AST\Test\TestNode;
use Oida\Environment\Environment;
use Oida\Exceptions\FehlerException;
use Oida\Interpreter\Interpreter;

set_exception_handler(function ($e) {
    if ($e instanceof FehlerException) {
        echo "🛑 Fehler: " . $e->getValue() . "\n";
        exit(1);
    }

    echo "Fatal error: Uncaught Exception: " . $e->getMessage() . "\n";
    exit(1);
});

$fileName = $_SERVER['REQUEST_URI'];
$path = parse_url($fileName, PHP_URL_PATH);
if ($root = getenv('OIDA_ROOT')) {
    $fileName = "$root$path.oida";
} else {
    $fileName = __DIR__ . "/../oida-web$path.oida";
}
if (!file_exists($fileName)) {
    if ($root = getenv('OIDA_ROOT')) {
        $fileName = "{$root}index.oida";
    } else {
        $fileName = __DIR__ . "/../oida-web/index.oida";
    }
}

if (!file_exists($fileName)) {
    echo "nix gfuntn";
    exit(1);
}


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
