<?php

namespace Oida\Parser\Class;

use Exception;
use Oida\AST\Class\ClassNode;
use Oida\AST\Class\ClassVariableNode;
use Oida\AST\Class\ConstructorNode;
use Oida\AST\Class\MethodNode;
use Oida\AST\Literals\IdentifierNode;
use Oida\Parser\BaseParser;
use Oida\Parser\ParseStatement;


class ParseClass extends BaseParser
{
    /**
     * @throws Exception
     */
    public function parse(int $tokenIndex): ?array
    {
        $this->currentIndex = $tokenIndex;

        if (!$this->match('T_CLASS')) return null;

        $this->expect('T_IDENTIFIER');

        $className = new IdentifierNode($this->tokens[$this->currentIndex - 1][1]);
        $this->expect('T_OPENING_BRACE');

        [$methods, $properties, $constructor, $this->currentIndex] = $this->parseClassBody($this->currentIndex);

        $constructor?->setClassName($className->getName());

        $this->currentIndex -= 1;

        $this->expect('T_CLOSING_BRACE');


        $classNode = new ClassNode($className, $methods, $properties, $constructor);


        return [$classNode, $this->currentIndex];
    }

    /**
     * @throws Exception
     */
    private function parseClassBody(int $tokenIndex): array
    {
        $this->currentIndex = $tokenIndex;
        $properties = [];
        $methods = [];
        $constructorNode = null;


        while (true) {
            if ($this->match('T_CLOSING_BRACE')) break;

            [$statement, $this->currentIndex] = (new ParseStatement($this->tokens))->parse($this->currentIndex);

            if ($statement instanceof MethodNode) {
                $methods[] = $statement;
            } elseif ($statement instanceof ConstructorNode) {
                $constructorNode = $statement;
            } elseif ($statement instanceof ClassVariableNode) {
                $properties[] = $statement;
            } else {
                throw new Exception("🛑\033[1;31mIrgendwas mit der Syntax in der Klasse stimmt nicht, würd ja gerne ne besser nachricht schreiben, aber selber keine ahnung 
                \n wie ich sowas track (vermutlich irgendwo ne \033[1;4;97m'}'\033[0m\033[1;31m vergssen oder irgendwo verschrieben oder so musst halt selber schauen)\033[0m");
            }
        }


        return [$methods, $properties, $constructorNode, $this->currentIndex];
    }


}