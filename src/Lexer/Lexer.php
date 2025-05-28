<?php

namespace Oida\Lexer;

class Lexer
{
    private string $input;
    private array $tokens = [];

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function tokenize(): array
    {
        $patterns = array_merge(
            $this->controlFlowTokens(),
            $this->logicTokens(),
            $this->databaseTokens(),
            $this->classTokens(),
            $this->operators(),
            $this->syntaxTokens(),
            $this->literalsAndValues(),
            $this->generalTokens()
        );

        $this->lookForTokens($patterns);
        return $this->tokens;
    }

    private function lookForTokens(array $patterns): void
    {
        $regex = '/' . implode('|', array_map(fn($p) => '(' . trim($p, '/') . ')', $patterns)) . '/';

        preg_match_all($regex, $this->input, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            foreach ($patterns as $type => $pattern) {
                if (preg_match('/^' . trim($pattern, '/') . '$/', $match[0])) {
                    $value = $match[0];
                    if ($type === 'T_STRING') {
                        $value = trim($value, '"');
                    }
                    $this->tokens[] = [$type, $value];
                    break;
                }
            }
        }
    }

    private function generalTokens(): array
    {
        return [
            'T_PRINT' => '/\boida\.sag\b/',
            'T_LET' => '/\bheast\b/',
            'T_METHOD' => '/\bhawara\b/',
            'T_RETURN' => '/\bspeicher\b/',
            'T_COMMENT' => '/\bkommentar\b/',
            'T_IDENTIFIER' => '/[a-zA-Z_]\w*/',
        ];
    }

    private function databaseTokens(): array
    {
        return [
            'T_DB_CONNECT' => '/\bverbinde\b/',
            'T_CREATE' => '/\berstell tabelle\b/',
            'T_VARCHAR' => '/\bcharaktäre\b/',
            'T_INT_DB' => '/\bzahl\b/',
            'T_PRIMARY_KEY' => '/\bhauptschlüssel\b/',
            'T_SELECT' => '/\bwähle\b/',
            'T_INSERT' => '/\bschreib in\b/',
            'T_VALUES' => '/\bwerte\b/',
            'T_UPDATE' => '/\bupdate\b/',
            'T_DELETE' => '/\blösche von\b/',
            'T_WHERE' => '/\bwo\b/',
            'T_QUERY_ACCESS' => '/\->/',
            'T_FETCH' => '/\bholma\b/',
        ];
    }

    private function controlFlowTokens(): array
    {
        return [
            'T_IF' => '/\bwenn\b/',
            'T_IF_ELSE' => '/\bsonst wenn\b/',
            'T_ELSE' => '/\bsonst\b/',
            'T_FOREACH' => '/(asdfwr|fürAlles)/',
            'T_FOR' => '/(asdasddssd|für)/',
            'T_WHILE' => '/(asdfwr|während)/',
            'T_FILTER' => '/\bnimmAusse\b/',
            'T_MAP' => '/\bkarte\b/',
            'T_AS' => '/\bals\b/',
        ];
    }

    private function logicTokens(): array
    {
        return [
            'T_TRUE' => '/\bbasst\b/',
            'T_FALSE' => '/\bsichaned\b/',
        ];
    }

    private function operators(): array
    {
        return [
            'T_COMPARISON_OPERATOR' => '/\b(gleich|isned|klanaglei|größerglei|klana|größer|und|oda)\b|!/',
            'T_ARITHMETIC_OPERATOR' => '/(plusplus|minusminus|mal|durch|plus|minus)/',
            'T_ARROW' => '/=>/',
            'T_ASSIGN' => '/\+=|-=|\*=|\/=|=/',
        ];
    }

    private function syntaxTokens(): array
    {
        return [
            'T_COLON' => '/\:/',
            'T_SEPARATOR' => '/\,/',
            'T_DOT' => '/\./',
            'T_OPENING_BRACKET' => '/\[/',
            'T_CLOSING_BRACKET' => '/\]/',
            'T_OPENING_BRACE' => '/\{/',
            'T_CLOSING_BRACE' => '/\}/',
            'T_OPENING_PARENTHESIS' => '/\(/',
            'T_CLOSING_PARENTHESIS' => '/\)/',
            'T_LINE_END' => '/;/',
        ];
    }

    private function literalsAndValues(): array
    {
        return [
            'T_NUMBER' => '/\d+/',
            'T_STRING' => '/"(?:.*?)"/',
        ];
    }

    private function classTokens():array
    {
        return [
            'T_CLASS' => '/\bklasse\b/',
            'T_NEW' => '/\bneu\b/',
            'T_CLASS_ACCESS' => '/\bgibMa\b/',
            'T_CLASS_PROPERTY_PUBLIC' => '/(asdasddadd|öffentlich)/',
            'T_CLASS_PROPERTY_PRIVATE' => '/\bprivat\b/',
            'T_THIS' => '/\bthis\b/',
            'T_CONSTRUCTOR' => '/\bBauMeister\b/',
        ];
    }
}