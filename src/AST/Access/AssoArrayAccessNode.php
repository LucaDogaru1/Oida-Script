<?php

namespace Oida\AST\Access;

use Exception;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class AssoArrayAccessNode extends ASTNode
{
    private string $name;

    private ASTNode $property;

    public function __construct(string $name, ASTNode $property)
    {
        $this->name = $name;
        $this->property = $property;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        $array = $env->getVariable($this->name);

        if (!is_array($array)) {
            throw new Exception("🛑 \033[1;31m'{$this->name}' ist kein Asso Array – wie willst da auf '{$this->property}' zugreifen?\033[0m");
        }

        $propertyKey = $this->property->evaluate($env);

        if (!array_key_exists($propertyKey, $array)) {
            throw new Exception("🛑 \033[1;31mKey '{$propertyKey}' existiert nicht in '{$this->name}'\033[0m");
        }

        return $array[$propertyKey];
    }
}