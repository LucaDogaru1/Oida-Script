<?php

namespace Oida\AST\Access;

use Exception;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class ArrayAccessNode extends ASTNode
{

    private string $arrayName;
    private mixed $value;

    public function __construct(string $arrayName, mixed $value)
    {
        $this->arrayName = $arrayName;
        $this->value = $value;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        $array = $env->getVariable($this->arrayName);

        $index = $this->value instanceof ASTNode
            ? $this->value->evaluate($env)
            : $this->value;

        if(!is_array($array)) throw new \Exception("🛑 \033[1;31m '{$this->arrayName}' is halt kein array wie willst da den index/key nehmen.\033[0m");
        if (!isset($array[$index])) {
            throw new \Exception("🛑 \033[1;31mIndex '{$index}' nicht im Array '{$this->arrayName}' vorhanden.\033[0m");
        }

        return $array[$index];
    }
}