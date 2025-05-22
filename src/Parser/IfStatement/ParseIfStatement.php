<?php

namespace Oida\Parser\IfStatement;

use Exception;
use Oida\AST\IfStatementNode;
use Oida\Parser\BaseParser;
use Oida\Parser\ConditionExpression\ParseConditionExpression;
use Oida\Parser\HelperMethods\HelperMethods;
use Oida\Parser\ParseCodeBlock;

class ParseIfStatement extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;
        if (!$this->match('T_IF')) return null;

        [$condition, $body, $this->currentIndex] = $this->handleConditionInParenthesis($this->currentIndex);

        $elseIfs = [];

        while ($this->match('T_IF_ELSE')) {
            [$elseIfCondition, $elseIfBody, $this->currentIndex] = $this->handleConditionInParenthesis($this->currentIndex);
            $elseIfs[] = ['condition' => $elseIfCondition, 'body' => $elseIfBody];
        }

        $else = null;
        if ($this->match('T_ELSE')) {
            [$elseBody, $this->currentIndex] = $this->handleBraces($this->currentIndex);
            $else = $elseBody;
        }

        return [new IfStatementNode($condition, $body, $elseIfs, $else), $this->currentIndex];
    }


    /**
     * @throws Exception
     */
    private function handleConditionInParenthesis(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_OPENING_PARENTHESIS')) return null;

        [$condition, $this->currentIndex] = (new ParseConditionExpression($this->tokens))->parse($this->currentIndex, 0);
        $this->expect('T_CLOSING_PARENTHESIS');
        [$body, $this->currentIndex] = $this->handleBraces($this->currentIndex);

        return [$condition, $body, $this->currentIndex];
    }

    /**
     * @throws Exception
     */
    private function handleBraces(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        $this->expect('T_OPENING_BRACE');
        [$body, $this->currentIndex] = (new ParseCodeBlock($this->tokens))->parse($this->currentIndex);
        $this->expect('T_CLOSING_BRACE');
        return [$body, $this->currentIndex];
    }
}