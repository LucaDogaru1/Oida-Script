<?php

namespace Oida\Parser\Database;

use Oida\AST\Database\ConnectionQueryNode;
use Oida\Parser\BaseParser;

class ParseConnection extends BaseParser
{
    /**
     * @throws \Exception
     */
    public function parse(int $tokenIndex)
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_DB_CONNECT')) return null;

        $this->expect('T_OPENING_PARENTHESIS');

        $this->expect('T_STRING');
        $host = $this->tokens[$this->currentIndex -1][1];

        $this->expect('T_SEPARATOR');

        $this->expect('T_STRING');
        $user = $this->tokens[$this->currentIndex -1][1];

        $this->expect('T_SEPARATOR');

        $this->expect('T_STRING');
        $password = $this->tokens[$this->currentIndex -1][1];

        $this->expect('T_SEPARATOR');

        $this->expect('T_STRING');
        $db = $this->tokens[$this->currentIndex -1][1];

        $this->expect('T_SEPARATOR');

        $this->expect('T_NUMBER');
        $port = $this->tokens[$this->currentIndex -1][1];

        $this->expect('T_CLOSING_PARENTHESIS');

        return [new ConnectionQueryNode($host, $user, $password, $db, $port), $this->currentIndex];
    }

}