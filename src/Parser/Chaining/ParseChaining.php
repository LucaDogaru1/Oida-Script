<?php

namespace Oida\Parser\Chaining;

use Exception;
use Oida\AST\Access\ArrayAccessNode;
use Oida\AST\Access\PropertyAccessNode;
use Oida\AST\Class\MethodCallNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Parser\Access\ParseArrayAccess;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;
use Oida\Parser\HelperMethods\HelperMethods;

class ParseChaining extends BaseParser
{

    private $baseNode;

    public function __construct(array $tokens, $baseNode)
    {
        parent::__construct($tokens);
        $this->baseNode = $baseNode;
    }

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): array
    {
        $this->currentIndex = $tokenIndex;
        $node = $this->baseNode;

        while (true) {
            if ($this->match('T_CLASS_ACCESS')) {
                if ($this->match('T_IDENTIFIER')) {
                    $methodName = new IdentifierNode($this->tokens[$this->currentIndex - 1][1]);

                    $this->expect('T_OPENING_PARENTHESIS');
                    $helper = new HelperMethods($this->tokens);
                    [$args, $this->currentIndex] = $helper->checkForMultipleExpressionsInParenthesis($this->currentIndex);
                    $this->expect('T_CLOSING_PARENTHESIS');

                    $node = new MethodCallNode($node, $methodName, $args);
                    continue;
                }
            }

            if ($this->match('T_OPENING_BRACKET')) {
                [$indexNode, $this->currentIndex] = (new ParseExpression($this->tokens))->parse($this->currentIndex);
                $this->expect('T_CLOSING_BRACKET');
                $node = new ArrayAccessNode($node, $indexNode);
                continue;
            }

            if ($this->match('T_DOT')) {
                if ($this->match('T_IDENTIFIER')) {
                    $propertyName = $this->tokens[$this->currentIndex - 1][1];

                    if ($this->match('T_OPENING_PARENTHESIS')) {
                        [$value, $this->currentIndex] = (new ParseExpression($this->tokens))->parse($this->currentIndex);
                        $this->expect('T_CLOSING_PARENTHESIS');

                        $node = new PropertyAccessNode($node, $propertyName, $value);
                        continue;
                    }

                    $node = new PropertyAccessNode($node, $propertyName, null);
                    continue;
                }
            }

            break;
        }

        return [$node, $this->currentIndex];
    }
}