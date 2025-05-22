<?php

namespace Oida\AST;

class VoidValue
{

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