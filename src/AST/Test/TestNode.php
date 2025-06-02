<?php

namespace Oida\AST\Test;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\CodeBlock\CodeBlockNode;
use Oida\Environment\Environment;

class TestNode extends ASTNode
{
    private string $name;

    private array $args;
    private CodeBlockNode $body;

    public function __construct(string $name,array $args, CodeBlockNode $body) {
        $this->name = $name;
        $this->args = $args;
        $this->body = $body;
    }
    /**
     * @throws Exception
     */public function evaluate(Environment $env)
    {
        $className = 'GeneratedTest_' . uniqid();
        $methodName = 'test' . $this->prepareMethodName($this->name);

        $phpBody = '';
        foreach ($this->body->getStatements() as $stmt) {
            if (method_exists($stmt, 'toPHP')) {
                $phpBody .= $stmt->toPHP() . "\n";
            } else {
                throw new Exception('Statement im Test-Body kann nicht in PHP übersetzt werden');
            }
        }

        $php = "<?php\n";
        $php .= "use PHPUnit\\Framework\\TestCase;\n";
        $php .= "class $className extends TestCase {\n";
        $php .= "    public function $methodName() {\n";
        $php .= "        " . $phpBody . "\n";
        $php .= "    }\n";
        $php .= "}\n";

        $filename = '/tmp/' . $className . '.php';
        file_put_contents($filename, $php);

        $output = shell_exec("./vendor/bin/phpunit $filename 2>&1");

        echo "🔬 Test '{$this->name}' läuft...\n";
        echo $output;
    }

    private function prepareMethodName(string $input): string
    {
        $input = preg_replace('/[^a-zA-Z0-9\s]/', '', $input);
        $words = preg_split('/\s+/', $input);
        $words = array_map('ucfirst', $words);
        return implode('', $words);
    }
}