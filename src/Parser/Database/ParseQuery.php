<?php

namespace Oida\Parser\Database;

use Exception;
use Oida\AST\Database\QueryNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\AST\Literals\StringNode;
use Oida\Parser\BaseParser;
use Oida\Parser\Expressions\ParseExpression;

class ParseQuery extends BaseParser
{

    /**
     * @throws Exception
     */
    public function  parse(int $tokenIndex, bool $inExpression): ?array
    {
        $this->currentIndex = $tokenIndex;

        if(!$this->match('T_IDENTIFIER')) return null;
        $conn = new IdentifierNode($this->tokens[$this->currentIndex -1][1]);

        if(!$this->match('T_QUERY_ACCESS')) return null;

        if (!$this->match('T_IDENTIFIER')) return null;

        $methodName = $this->tokens[$this->currentIndex - 1][1];
        if (strtolower($methodName) !== 'query') return null;

        $this->expect('T_OPENING_PARENTHESIS');

        $expr = (new ParseExpression($this->tokens))->parse($this->currentIndex);
        if(!$expr) throw new Exception("🛑 \033[1;91m'Musst schon einen Query schreiben ??.\033[0m");
        [$query, $this->currentIndex] = $expr;

        if ($query instanceof StringNode) {
            $sql = $query->getValue();
            if (stripos($sql, "SELECT") === 0 && !$inExpression) {
                throw new \Exception("🛑 \033[1;91mSELECT-Query muss in eine Variable gespeichert oder zurückgegeben werden, is ja logisch oder ?\033[0m");
            }
        }
        $this->expect('T_CLOSING_PARENTHESIS');

        if(!$inExpression)  $this->expect('T_LINE_END');

        return [new QueryNode($conn, $query), $this->currentIndex];
    }

}