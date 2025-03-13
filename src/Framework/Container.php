<?php

declare(strict_types=1);

namespace Framework;

use Closure;
use ReflectionClass;

class Container
{
    private array $registry = [];

    public function set(string  $name, Closure $function)
    {
        $this->registry[$name] = $function;
    }

    public function get(string $class_name)
    {

        if (array_key_exists($class_name, $this->registry)) {

            return $this->registry[$class_name]();
        }

        $dependencies = [];

        $reflection = new ReflectionClass($class_name);

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class_name;
        }

        foreach ($constructor->getParameters() as $parameter) {

            $name = $parameter->getType()->getName();

            $dependencies[] = $this->get($name);
        }

        return new $class_name(...$dependencies);
    }
}
