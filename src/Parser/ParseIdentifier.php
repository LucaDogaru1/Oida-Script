<?php

namespace Oida\Parser;

use Exception;
use Oida\AST\IdentifierNode;

class ParseIdentifier extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;
        if (!$this->match('T_IDENTIFIER')) return null;

        $token = $this->tokens[$this->currentIndex - 1];
        $name = $token[1];

        $node = new IdentifierNode($name);
        return [$node, $this->currentIndex];
    }
}