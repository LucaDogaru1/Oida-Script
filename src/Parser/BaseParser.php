<?php

namespace Oida\Parser;

use Exception;

abstract class BaseParser
{
    protected array $tokens;
    protected int $currentIndex = 0;
    protected static ?string $globalContext = null;

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    protected function advance(): void
    {
        $this->currentIndex++;
    }

    protected function currentToken(): ?array
    {
        return $this->tokens[$this->currentIndex] ?? null;
    }

    protected function match(string $type, bool $consume = true): bool
    {
        $token = $this->currentToken();
        if ($token && $token[0] === $type) {
            if($consume) $this->advance();
            return true;
        }
        return false;
    }

    /**
     * @throws Exception
     */
    protected function expect(string $type): array
    {
        $token = $this->currentToken();

        if (!$token || $token[0] !== $type) {
            if ($type == 'T_LINE_END') throw new Exception("🛑\033[1;31mWas fehlt? Genau. Das heilige \033[1;4;97m';'\033[0m\033[1;31m. Ohne des kummst ned amal in Java ins Wirtshaus. (Java ist auch ne Insel)\033[0m");
            if($type == 'T_OPENING_BRACE') throw new Exception("🛑\033[1;31mDu musst ma halt scho \033[1;4;97m'{'\033[0m\033[1;31m geben vor \033[1;4;97m'$token[1]'\033[0m\033[1;31m ?\033[0m");
            if($type == 'T_CLOSING_PARENTHESIS') throw new Exception("🛑\033[1;31mDu musst ma halt scho \033[1;4;97m')'\033[0m\033[1;31m geben vor \033[1;4;97m'$token[1]'\033[0m\033[1;31m ?\033[0m");
            if($type == 'T_OPENING_PARENTHESIS') throw new Exception("🛑\033[1;31mDu musst ma halt scho \033[1;4;97m'('\033[0m\033[1;31m geben ? Selber schuld Zeile musst selber finden\033[0m");
            throw new Exception("Hallo, du hast nen fehler ich erwarte den typ $type, aber du hast mir den type gegeben " . ($token[0] ?? 'nichts'));
        }
        $this->advance();
        return $token;
    }

    public static function setContext(?string $context): void
    {
        self::$globalContext = $context;
    }

    public static function getContext(): ?string
    {
        return self::$globalContext;
    }
}