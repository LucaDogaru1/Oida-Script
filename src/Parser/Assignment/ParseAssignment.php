<?php

namespace Oida\Parser\Assignment;

use Exception;
use Oida\AST\Assignment\AssignmentNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Environment\Environment;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;

class ParseAssignment extends BaseParser
{

    public function __construct(array $tokens, ?Environment $env = null) {
        parent::__construct($tokens, $env);
    }

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_IDENTIFIER')) return null;
        $left = $this->tokens[$this->currentIndex -1][1];

        if(!$this->match('T_ASSIGN')) return null;

        $expression = (new ParseExpression($this->tokens))->parse($this->currentIndex);
        if (!$expression) {
            throw new Exception("🛑 \033[1;31mNach '=' brauchst halt schon a Ausdruck, oida.\033[0m");
        }
        [$valueNode, $this->currentIndex] = $expression;

        $this->expect('T_LINE_END');

        return [new AssignmentNode($left, $valueNode), $this->currentIndex];
    }

}