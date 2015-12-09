<?php

namespace Mapped;

/**
 * ReflectionFactory (Experimental).
 */
class ReflectionFactory extends Factory
{
    private $mappings = [];

    /**
     * @see Factory::__construct()
     */
    public function __construct(array $extensions = [])
    {
        parent::__construct($extensions);
        $this->mappings = [
            'string' => $this->string(),
            'int' => $this->int(),
            'float' => $this->float(),
            'bool' => $this->bool(),
            'array' => $this->mapping()->multiple(),
        ];
    }

    /**
     * Creates a mapping from given type.
     *
     * @param string $type The type
     *
     * @return Mapping
     */
    public function of($type)
    {
        $isArray = '[]' === substr($type, -2);
        if ($isArray) {
            $type = substr($type, 0, -2);
        }

        if (array_key_exists($type, $this->mappings)) {
            $mapping = $this->mappings[$type];
        } else {
            $refl = new \ReflectionClass($type);
            $mapping = new Mapping(new Emitter, $this->extensions);

            foreach ($refl->getProperties() as $prop) {
                if ($child = $this->mappingFromProp($prop)) {
                    $mapping->addChild($prop->getName(), $child);
                }
            }

            $mapping->transformTo($type);
        }

        if ($isArray) {
            $mapping = $mapping->multiple();
        }

        return $mapping;
    }

    /**
     * Creates a mapping from `ReflectionProperty`.
     *
     * @param ReflectionProperty $prop The reflection property
     *
     * @return Mapping
     */
    private function mappingFromProp(\ReflectionProperty $prop)
    {
        $comment = $prop->getDocComment();
        preg_match('/@var\s+(.*)[\s|\n]/', $comment, $matches);

        if (!isset($matches[1])) {
            return;
        }

        $annotation = $matches[1];
        $type = $annotation;

        if (ctype_upper($type[0]) && !class_exists($type)) {
            $ns = $prop->getDeclaringClass()->getNamespaceName();
            $type = $ns . '\\' . $type;
        }

        return $this->of($type)->optional();
    }
}
