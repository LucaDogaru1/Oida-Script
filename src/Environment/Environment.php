<?php

namespace Oida\Environment;

use Exception;
use Oida\AST\Class\ConstructorNode;

class Environment
{
    private array $variables = [];
    private array $methods = [];
    private array $classes = [];
    private array $classProperties = [];

    private ?string $insideClass = null;

    private bool $inConstructor = false;

    private ?Environment $parent = null;

    private ?object $currentObject = null;
    private bool $inFunction = false;

    public function __construct(?Environment $parent = null)
    {
        $this->parent = $parent;
    }

    public function setParent(Environment $parent): void
    {
        $this->parent = $parent;
    }


    public function defineVariable(string $name, $value): void
    {

        $this->variables[$name] = $value;
    }

    /**
     * @throws Exception
     */
    public function getVariable(string $name)
    {
        if (array_key_exists($name, $this->variables)) {
            return $this->variables[$name];
        }

        if ($this->parent != null) {
            return $this->parent->getVariable($name);
        }

        return throw new Exception("Unknown identifier: " . $name);
    }

    public function defineMethod(string $name, $body, $parameters, string $visibility): void
    {
        $this->methods[$name] = [
            "body" => $body,
            "parameters" => $parameters ?? [],
            'visibility' => $visibility,
        ];
    }

    /**
     * @throws Exception
     */

    public function defineFunction(string $name, $body, $parameters): void
    {
        $this->methods[$name] = [
            "body" => $body,
            "parameters" => $parameters ?? []
        ];
    }

    /**
     * @throws Exception
     */
    public function getFunction(string $name): ?array
    {
        if (array_key_exists($name, $this->methods)) {
            return $this->methods[$name];
        }

        if ($this->parent != null) {
            return $this->parent->getFunction($name);
        }

        return throw new Exception("Gibt keine Function die so heißt:" . $name);
    }


    public function defineClass(string $name, $methods, $properties, ?ConstructorNode $constructor = null): void
    {
        $this->classes[$name] = [
            'methods' => $methods,
            'properties' => $properties,
            'constructor' => $constructor,
            'isClass' => true
        ];
    }

    /**
     * @throws Exception
     */
    public function getClass(string $name): ?array
    {
        if (array_key_exists($name, $this->classes)) {
            return $this->classes[$name];
        }

        if ($this->parent != null) {
            return $this->parent->getClass($name);
        }

        throw new Exception("Gibt es eine Klasse mit dem Namen  " . $name. " ?");
    }

    /**
     * @throws Exception
     */
    public function getCurrentObject(): ?object
    {
        return $this->currentObject ?? null;
    }
    public function defineClassProperty(string $name, $value, $visibility): void
    {
        if ($this->parent !== null) {
            $this->parent->defineClassProperty($name, $value, $visibility);
            return;
        }

        $this->classProperties[$name] = [
            'visibility' => $visibility,
            'value' => $value
        ];
    }

    /**
     * @throws Exception
     */
    public function getObjectProperty(string $propertyName)
    {
        $currentObject = $this->getCurrentObject();
        if (!property_exists($currentObject, $propertyName)) {
            throw new Exception("Property '$propertyName' does not exist on the current object.");
        }
        return $currentObject->propertyName;
    }

    /**
     * @throws Exception
     */
    public function setObjectProperty(string $propertyName, $value): void
    {
        $currentObject = $this->getCurrentObject();
        if (!property_exists($currentObject, $propertyName)) {
            throw new Exception("Property '$propertyName' does not exist on the current object.");
        }
        $currentObject->$propertyName = $value;
    }

    public function setCurrentObject(object $object): void
    {
        $this->currentObject = $object;
    }


    public function hasClass(string $className): bool
    {
        return isset($this->classes[$className]);
    }

    public function methodExists(string $methodName): bool
    {
        return isset($this->methods[$methodName]);
    }

    public function hasVariable(string $name): bool
    {
        return isset($this->variables[$name]);
    }

    public function enterConstructor(): void {
        $this->inConstructor = true;
    }

    public function leaveConstructor(): void {
        $this->inConstructor = false;
    }

    public function isInConstructor(): bool {
        return $this->inConstructor;
    }

    public function hasFunction(string $name): bool
    {
        return isset($this->methods[$name]);
    }

    public function setInsideClass(?string $className): void
    {
        $this->insideClass = $className;
    }

    public function insideClass(): bool
    {
        return $this->insideClass !== null;
    }

    public function getInsideClassName(): ?string
    {
        return $this->insideClass;
    }


    public function enterFunction(): void
    {
        $this->inFunction = true;
    }
    public function inFunction(): bool
    {
        return $this->inFunction;
    }

    public function leaveFunction(): void
    {
        $this->inFunction = false;
    }


}