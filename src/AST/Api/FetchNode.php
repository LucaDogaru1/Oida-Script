<?php

namespace Oida\AST\Api;

use Exception;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class FetchNode extends ASTNode
{
    private string $apiLink;

    public function __construct(string $apiLink)
    {
        $this->apiLink = $apiLink;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiLink);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("🛑Fehler beim API-Aufruf: {$error}");
        }
        curl_close($ch);

        $decoded = json_decode($response, true);
        return $decoded ?? $response;
    }
}