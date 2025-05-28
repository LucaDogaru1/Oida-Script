<?php

namespace Oida\AST\Database;

use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class ConnectionQueryNode extends ASTNode
{

    private string $host;
    private string $user;
    private string $password;

    private string $db;
    private int $port;


    public function __construct(string $host, string $user, string $password, string $db,  int $port)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->db = $db;
        $this->port = $port;
    }

    public function evaluate(Environment $env): \mysqli
    {
        $conn = mysqli_connect($this->host, $this->user, $this->password, $this->db, $this->port);

        if (!$conn) {
            die("Connection ist fehlgeschlagen: " . mysqli_connect_error() . "\n");
        }

        echo "Connection ist eingerichtet \n";

        return $conn;
    }
}