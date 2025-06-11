<?php

namespace Oida\AST\Throw;

use Oida\AST\ASTNode;
use Oida\Environment\Environment;
use Oida\Exceptions\FehlerException;

class ThrowNode extends ASTNode
{

    private ASTNode $message;

    public function __construct(ASTNode $message)
    {
        $this->message = $message;
    }

    /**
     * @throws FehlerException
     */
    public function evaluate(Environment $env)
    {
        $value = $this->message->evaluate($env);
        throw new FehlerException($value);
    }
}