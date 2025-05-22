<?php

namespace Oida\Parser\BinaryExpression;

use Exception;

class OperatorPrios
{
    /**
     * @throws Exception
     */
    public static function getInfo(string $op): array
    {
        return match ($op) {
            'plusplus', 'minusminus', 'plus', 'minus' => ['prio' => 1, 'assoc' => 'left'],
            'mal', 'durch' => ['prio' => 2, 'assoc' => 'left'],
            default => throw new Exception("Unbekannter Operator: {$op}"),
        };
    }
}