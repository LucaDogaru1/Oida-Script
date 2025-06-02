<?php

namespace Oida\Parser\Import;

use Oida\AST\Import\ImportNode;
use Oida\Parser\BaseParser;

class ParseImport extends BaseParser
{

    /**
     * @throws \Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_IMPORT')) return null;

        $this->expect('T_STRING');
        $path = $this->tokens[$this->currentIndex -1][1];

        $this->expect('T_LINE_END');

        return [new ImportNode($path),$this->currentIndex];
    }

}