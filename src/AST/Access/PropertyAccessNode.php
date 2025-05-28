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
            'hat' => is_array($array) ? array_key_exists($value, $array) : $this->throeException('hat'),
            'erstesElement' => is_array($array) ? $array[0] : $this->throeException('erstesElement'),
            'letztesElement' => is_array($array) ? $array[array_key_last($array)] : $this->throeException('letztesElement'),
            'irgendeinElement' => is_array($array) ? $array[array_rand($array)] : $this->throeException('irgendeinElement'),
            'mische' => is_array($array) ? (function () use ($array) {
                shuffle($array);
                return $array;
            })() : $this->throeException('mische'),
            'ohneDuplikat' => is_array($array) ? array_values(array_unique($array)) : $this->throeException('ohneDuplikate'),
            'sortiere' => is_array($array) ? (function () use ($array) {
                sort($array);
                return $array;
            })() : $this->throeException('sortiere'),
            'sortiereAbsteigend' => is_array($array) ? (function () use ($array) {
                rsort($array);
                return $array;
            })() : $this->throeException('sortiereAbsteigend'),
            'indexVon' => is_array($array) ? array_search($value, $array) : $this->throeException('indexVon'),
            'flach' => is_array($array) ? $this->flach($array) : $this->throeException('flach'),
            'entferne' => is_array($array) ? array_values(array_filter($array, fn($item) => $item !== $value)) : $this->throeException('entferne'),
            'gibRein' => is_array($array) ? [...$array, $value] : $this->throeException('gibRein'),
            'ersetz' => is_array($array) && is_array($value) && count($value) === 2
                ? array_map(fn($item) => $item === $value[0] ? $value[1] : $item, $array)
                : $this->throeException('ersetze'),
            'kombinier' => is_array($array) && is_array($value)
                ? array_merge($array, $value)
                : $this->throeException('kombinier'),
            'zuText' => match (true) {
                is_array($array) => implode(is_string($value) ? $value : '', $array),
                is_bool($array) => $array ? 'basst' : 'sichaned',
                is_scalar($array) => strval($array),
                is_object($array) && method_exists($array, '__toString') => (string)$array,
                default => $this->throeException('zuText'),
            },
            'istZahl' => is_numeric($array),
            'textHat' => is_string($array) && is_string($value)
                ? str_contains($array, $value)
                : $this->throeException('stringHat'),
            'istArray' =>  is_array($array),
            'istAssoArray' => is_array($array) && array_keys($array) !== range(0, count($array) - 1),
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

    private function flach(array $array): array
    {
        $result = [];

        foreach ($array as $element) {
            if (is_array($element)) {
                $result = [...$result, ...$element];
            } else {
                $result[] = $element;
            }
        }
        return $result;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getArrayName():string
    {
        return $this->arrayName;
    }
}