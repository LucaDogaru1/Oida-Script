<?php

namespace Oida\AST\Database;

use Exception;
use Oida\AST\ASTNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Environment\Environment;

class QueryNode extends ASTNode
{
    private IdentifierNode $conn;
    private ASTNode $query;

    public function __construct(IdentifierNode $conn, ASTNode $query)
    {
        $this->conn = $conn;
        $this->query = $query;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        $conn = $env->getVariable($this->conn->getName());
        $query = $this->query->evaluate($env);

        if (!$conn instanceof \mysqli) {
            throw new Exception("Variable '{$this->conn->getName()}' ist keine gültige Datenbankverbindung.");
        }

        $result = $conn->query($query);

        if (!$result) {
            throw new Exception("SQL-Fehler: " . mysqli_error($conn));
        }

        if (stripos($query, 'select') === 0 ||
            stripos($query, 'show') === 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return true;
    }
}