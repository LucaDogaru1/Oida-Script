<?php

namespace Oida\AST;

use Exception;
use Oida\AST\Literals\IdentifierNode;
use Oida\Environment\Environment;

class BinaryOperationNode extends ASTNode
{

    private ASTNode  $left;
    private string $operator;
    private ?ASTNode  $right;

    public function __construct(ASTNode  $left, string $operator, ?ASTNode $right = null)
    {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        if ($this->right === null) {
            if (!$this->left instanceof IdentifierNode) {
                throw new Exception("🛑 '{$this->operator}' braucht eine Variable als linken Operand.");
            }

            $name = $this->left->getName();
            $value = $env->getVariable($name);

            $newValue = match ($this->operator) {
                'plusplus' => $value + 1,
                'minusminus' => $value - 1,
                default => throw new Exception("🛑 braucht plusplus oder minusminus ...'{$this->operator}'"),
            };

            $env->defineVariable($name, $newValue);
            return $newValue;
        }

        $leftValue = $this->left->evaluate($env);
        $rightValue = $this->right->evaluate($env);

        return match ($this->operator) {
            'plus' => $leftValue + $rightValue,
            'minus' => $leftValue - $rightValue,
            'mal' => $leftValue * $rightValue,
            'durch' => $rightValue != 0 ? $leftValue / $rightValue : throw new Exception("🛑 \033[1;31mDivision durch \033[1;97m0\033[0m\033[1;31m? Echt jetzt?\033[0m"),
            default => throw new Exception("🛑 \033[1;31mWas willstn mit so einem Operator anfangen?\033[0m \033[1;97m'{$this->operator}'\033[0m \033[1;31mis")
        };
    }

    /**
     * @throws Exception
     */
    public function toPHP(): string {
        $phpOperator = match ($this->operator) {
            'plus'  => '+',
            'minus' => '-',
            'mal'   => '*',
            'durch' => '/',
            default => throw new \Exception("Unbekannter Operator: {$this->operator}")
        };

        return '(' . $this->left->toPHP() . ' ' . $phpOperator . ' ' . $this->right->toPHP() . ')';
    }
}