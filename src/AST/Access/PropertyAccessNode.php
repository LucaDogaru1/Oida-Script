<?php

namespace Oida\AST\Access;

use Exception;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class PropertyAccessNode extends ASTNode
{

    private string $arrayName;
    private string $property;
    private mixed $value = null;

    public function __construct(string $arrayName, string $property, mixed $value = null)
    {
        $this->arrayName = $arrayName;
        $this->property = $property;
        $this->value = $value ?? null;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        $array = $env->getVariable($this->arrayName);
        $value = null;

        if ($this->value) {
            $value = $this->value instanceof ASTNode ? $this->value->evaluate($env) : $this->value;
        }

        return match ($this->property) {
            'anzahl' => is_array($array) ? count($array) : $this->throeException('länge'),
            'leer' => is_array($array) ? empty($array) : $this->throeException('leer'),
            'hatWert' => is_array($array) ? array_key_exists($value, $array) : $this->throeException('hatWert'),
            default => throw new Exception("🛑 Was soll das für eine Property sein '{$this->property}' für '{$this->arrayName}'"),
        };
    }


    /**
     * @throws Exception
     */
    private function throeException(string $text)
    {
        throw new \Exception("🛑 \033[1;31m'{$this->arrayName}'\033[0m \033[1;97mhat keine property '{$text}',\033[0m \033[1;31mweils halt kein Array is ...\033[0m");
    }
}