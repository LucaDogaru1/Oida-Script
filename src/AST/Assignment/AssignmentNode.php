<?php

namespace Oida\AST\Assignment;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\VoidValue;
use Oida\Environment\Environment;

class AssignmentNode extends ASTNode
{

    private string $name;
    private ASTNode $value;

    public function __construct(string $name, ASTNode $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): void
    {
        $evaluatedValue = $this->value->evaluate($env);

        if ($evaluatedValue instanceof VoidValue) {
            throw new \Exception(
                "🛑 \033[1;31mIn '{$this->name}' kannst ned einfach 'void' speichern.\033[0m\n" .
                "\033[1;97mWas soll das bringen? Willst ins Nix was reinschreiben?\033[0m"
            );
        }
        $env->defineVariable($this->name, $evaluatedValue);
    }
}