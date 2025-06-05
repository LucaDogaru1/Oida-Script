<?php

namespace Oida\Parser;

use Exception;
use Oida\AST\CodeBlock\CodeBlockNode;

class ParseCodeBlock extends BaseParser
{

    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): array
    {
        $this->currentIndex = $tokenIndex;

        $statements = [];

        while(true) {
            $statement = (new ParseStatement($this->tokens, $this->env))->parse($this->currentIndex);
            if(!$statement) break;
            $statements[] = $statement[0];
            $this->currentIndex = $statement[1];
        }
        return [new CodeBlockNode($statements), $this->currentIndex];
    }

}