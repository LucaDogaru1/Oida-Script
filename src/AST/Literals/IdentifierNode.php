<?php

namespace Oida\AST\Literals;

use Exception;
use Oida\AST\ASTNode;
use Oida\Environment\Environment;

class IdentifierNode extends ASTNode
{
    private string $name;

    public function __construct(string $name, string $type = 'identifier')
    {
        $this->type = $type;
        $this->name = $name;
    }

    /**
     * @throws Exception
     */
    public function evaluate(Environment $env)
    {
        $this->checkError($env);

        $currentObject = $env->getCurrentObject();
        if ($currentObject && $currentObject->hasProperty($this->name)) {
            return $currentObject->getProperty($this->name);
        }

        if ($env->hasVariable($this->name)) {
            return $env->getVariable($this->name);
        }

        if ($env->hasClass($this->name)) {
            return $env->getClass($this->name);
        }

        if ($env->hasFunction($this->name)) {
            return $env->getFunction($this->name);
        }

        return null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws Exception
     */
    private function checkError(Environment $env): void
    {
        $currentObject = $env->getCurrentObject();

        $notDefined = !$env->hasVariable($this->name)
            && !$env->hasFunction($this->name)
            && !$env->hasClass($this->name)
            && (!$currentObject || !$currentObject->hasProperty($this->name));

        if ($notDefined) {
            if ($env->isInConstructor()) {
                throw new Exception("🛑 \033[1;4;97m{$this->name}\033[0m \033[90m→\033[0m \033[91mexistiert nicht mal – wie willst du ihn dann im Konstruktor setzen?\033[0m");
            } else {
                throw new Exception("🛑 \033[1;91mUnbekannter Bezeichner\033[0m \033[1;97m'{$this->name}'\033[0m.");
            }
        }
    }


}
