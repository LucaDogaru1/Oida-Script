<?php

namespace Oida\AST;

use Exception;
use Oida\AST\Literals\IdentifierNode;
use Oida\Environment\Environment;

class VariableNode extends ASTNode
{

    private IdentifierNode $name;
    private ASTNode $value;

    public function __construct(IdentifierNode $name, ASTNode $value)
    {
        $this->type = 'variable';
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env): void
    {
        $name = $this->name->getName();
        $value = $this->value->evaluate($env);
        if ($value instanceof VoidValue) {
            throw new \Exception(
                "🛑 \033[1;31mHÄ??,\033[0m \033[1;31mdu kannst nicht (\033[1;97mvoid\033[1;31m) in \033[1;97m'{$this->name->getName()}'\033[1;31m speichern.\n" .
                "Was soll das bringen? Willst in Luft was reinschreiben, oder was?\033[0m"
            );
        }
        $env->defineVariable($name, $value);
    }

    public function getName(): string
    {
        return $this->name->getName();
    }

    public function getValue(): ASTNode
    {
        return $this->value;
    }

}