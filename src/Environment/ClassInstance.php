<?php

namespace Oida\Environment;

use Exception;
use Oida\AST\ASTNode;

class ClassInstance
{
    private array $methods;
    private array $properties;
    private string $className;



    public function __construct(string $className, array $methods, array $propertyNodes)
    {
        $this->className = $className;
        $this->methods = $methods;
        $this->properties = [];

        foreach ($propertyNodes as $propertyNode) {
            $name = $propertyNode->getName();
            $value = $propertyNode->getValue();
            $this->properties[$name] = [
                'value' => $value,
                'visibility' => $propertyNode->getVisibility()
            ];
        }
    }

    /**
     * @throws Exception
     */
    public function getMethod(string $name)
    {
        foreach ($this->methods as $method) {
            if ($method->getMethodName() === $name) {
                return $method;
            }
        }
        throw new Exception("Gibt keine Methode mit dem Namen: $name");
    }

    /**
     * @throws Exception
     */
    public function getProperty(string $name): mixed
    {
        if (!isset($this->properties[$name])) {
            throw new Exception("Gibt keine Property mit dem Namen: $name");
        }

        return $this->properties[$name]['value'] ?? null;
    }

    public function setProperty(string $name, $value, $visibility): void
    {
        if (is_array($value) && isset($value['value']) && isset($value['visibility'])) {
            $this->properties[$name] = $value;
            return;
        }
        $this->properties[$name] = [
            'value' => $value,
            'visibility' => $visibility
        ];
    }

    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @throws Exception
     */
    public function getPropertyVisibility(string $name): string
    {
        if (!isset($this->properties[$name])) {
            throw new Exception("Property '$name' existiert nicht.");
        }

        return $this->properties[$name]['visibility'] ?? 'public';
    }
    public function getClassName(): string {
        return $this->className;
    }
}
