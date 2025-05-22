<?php

namespace Oida\Parser\Variable;

use Exception;
use Oida\AST\IdentifierNode;
use Oida\AST\VariableNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;

class ParseVariable extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_LET')) return null;

        $this->expect('T_IDENTIFIER');
        $varName = (new IdentifierNode($this->tokens[$this->currentIndex - 1][1]));

        $this->expect('T_ASSIGN');
        [$value, $this->currentIndex] = (new ParseExpression($this->tokens))->parse($this->currentIndex);

        $this->expect('T_LINE_END');

        $variableNode = new VariableNode($varName, $value);

        return [$variableNode, $this->currentIndex];
    }

}