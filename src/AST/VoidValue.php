<?php

namespace Oida\AST;


use Oida\Environment\Environment;
class VoidValue extends ASTNode
{

    public function evaluate(Environment $env): mixed
    {
        return null;
    }

    //falls irgenwas schief geht zur sicherheit drinnen lassen
    public function __toString(): string
    {
        return '';
    }

    public function getType(): string
    {
        return 'void';
    }
}