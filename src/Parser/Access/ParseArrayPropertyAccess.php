<?php

namespace Oida\Parser\Access;

use Exception;
use Oida\AST\Access\PropertyAccessNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;
use Oida\Parser\Literals\ParseIdentifier;

class ParseArrayPropertyAccess extends BaseParser
{

    private array $allowedProperties = [
        'anzahl',
        'leer',
        'hat',
        'erstesElement',
        'letztesElement',
        'irgendeinElement',
        'mische',
        'ohneDuplikat',
        'sortiere',
        'sortiereAbsteigend',
        'indexVon',
        'flach',
        'entferne',
        'gibRein',
        'ersetz',
        'kombinier',
        'zuText',
        'istZahl',
        'textHat',
        'istArray',
        'istAssoArray',
        'char',
        'EXPLODIER',
        'zuJson',
        'decode_json'
    ];

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_IDENTIFIER')) return null;
        [$arrayName, $this->currentIndex] = (new ParseIdentifier($this->tokens))->parse($this->currentIndex - 1);

        if(!$this->match('T_DOT')) return null;

        if(!$this->match('T_IDENTIFIER')) return null;

        $propertyName = $this->tokens[$this->currentIndex -1][1];

        //die If abfrage geamcht da es sich sonst mit dem AssoArrayAcces überkreutzt wegen token T_DOT
        if (!in_array($propertyName, $this->allowedProperties)) {
            return null;
        }

        if(!$this->match('T_OPENING_PARENTHESIS')) return [new PropertyAccessNode($arrayName, $propertyName, null), $this->currentIndex];

        [$value, $this->currentIndex] = (new ParseExpression($this->tokens))->parse($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');
        return [new PropertyAccessNode($arrayName, $propertyName, $value), $this->currentIndex];
    }
}