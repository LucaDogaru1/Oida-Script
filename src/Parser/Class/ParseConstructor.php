<?php

namespace Oida\Parser\Class;

use Exception;
use Oida\AST\Assignment\AssignmentNode;
use Oida\AST\Class\ConstructorNode;
use Oida\Parser\BaseParser;
use Oida\Parser\HelperMethods\HelperMethods;
use Oida\Parser\ParseCodeBlock;

class ParseConstructor extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_CONSTRUCTOR')) return null;
        $name = $this->tokens[$this->currentIndex - 1][1];


        $this->expect('T_OPENING_PARENTHESIS');
        $helperMethod = new HelperMethods($this->tokens);
        [$args, $this->currentIndex] = $helperMethod->checkForMultipleExpressionsInParenthesis($this->currentIndex);

        $this->expect('T_CLOSING_PARENTHESIS');

        $this->expect('T_OPENING_BRACE');

        [$body, $this->currentIndex] = (new ParseCodeBlock($this->tokens, $this->env))->parse($this->currentIndex);

        foreach ($body->getStatements() as $stmt) {
            if ($stmt instanceof AssignmentNode) {
                throw new Exception("🛑 \033[1;91mhä?\033[0m Im Konstruktor ned einfach \033[1;93m'x = ...'\033[0m. \033[1;92mthis:x\033[0m, oida.");
            }
        }

        $this->expect('T_CLOSING_BRACE');

        $constructorNode = new ConstructorNode($name, $args, $body);
        return [$constructorNode, $this->currentIndex];
    }

}