<?php

namespace Oida\AST\Access;

use Exception;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class ArrayAccessNode extends ASTNode
{

    private ASTNode $arrayExpr;
    private mixed $indexExpr;

    public function __construct(ASTNode $arrayExpr, mixed $indexExpr)
    {
        $this->arrayExpr = $arrayExpr;
        $this->indexExpr = $indexExpr;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        $array = $this->arrayExpr->evaluate($env);

        if ($array instanceof ASTNode) {
            $array = $array->evaluate($env);
        }

        $index = $this->indexExpr instanceof ASTNode
            ? $this->indexExpr->evaluate($env)
            : $this->indexExpr;

        if (is_array($array)) {
            if (!array_key_exists($index, $array)) {
                throw new Exception("🛑 Index '{$index}' nicht im Array vorhanden.");
            }
            return $array[$index];
        }

        if (is_string($array)) {
            if (!is_numeric($index)) {
                throw new Exception("🛑 String-Zugriff mit nicht-numerischem Index '{$index}'.");
            }

            if ($index < 0 || $index >= strlen($array)) {
                throw new Exception("🛑 Index '{$index}' außerhalb des Strings.");
            }

            return $array[$index];
        }

        throw new Exception("🛑 Zugriff auf Index bei keinem Array oder String.");
    }

}