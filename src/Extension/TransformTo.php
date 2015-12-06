<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Transformer\CallbackTransformer;
use Mapped\Mapping;

/**
 * TransformTo (Experimental).
 */
class TransformTo implements ExtensionInterface
{
    /**
     * Transforms the data to given class via setter and getter methods.
     * Important: If you pass the `object` param as string, then the class must
     * be initializable without constructor arguments.
     *
     * @param Mapping       $mapping The mapping object
     * @param string|object $object  The class name or an object
     *
     * @return Mapping
     */
    public function transformTo(Mapping $mapping, $object)
    {
        $refl = new \ReflectionClass($object);
        if (is_string($object)) {
            $constr = $refl->getConstructor();
            if (!$constr || 0 === $constr->getNumberOfRequiredParameters()) {
                $object = $refl->newInstance();
            } else {
                $object = $refl->newInstanceWithoutConstructor();
            }
        }

        $mapping->transform(new CallbackTransformer(function ($data) use ($refl, $object, $mapping) {
            if ($object instanceof \stdClass) {
                return json_decode(json_encode($data));
            }

            foreach ($mapping->getChildren() as $name => $child) {
                if (array_key_exists($name, $data) && null !== $data[$name]) {
                    $this->setValue($refl, $object, $name, $data[$name]);
                }
            }

            return $object;
        }, function ($data) use ($refl, $object, $mapping) {
            if (!$data instanceof $object) {
                return;
            }

            if ($object instanceof \stdClass) {
                return json_decode(json_encode($data), true);
            }

            $result = [];
            foreach ($mapping->getChildren() as $name => $child) {
                $result[$name] = $this->getValue($refl, $data, $name);
            }

            return $result;
        }, false));

        return $mapping;
    }

    /**
     * Sets the given value into the object.
     *
     * @param ReflectionClass $refl   The reflection class
     * @param object          $object The object
     * @param string          $name   The property name
     * @param mixed           $value  The value
     */
    private function setValue(\ReflectionClass $refl, $object, $name, $value)
    {
        $setter = 'set' . ucfirst($name);
        if (method_exists($object, $setter)) {
            return $object->$setter($value);
        }

        $prop = $refl->getProperty($name);
        if (!$prop->isPublic()) {
            $prop->setAccessible(true);
        }

        $prop->setValue($object, $value);
    }

    /**
     * Gets a value from given object.
     *
     * @param ReflectionClass $refl   The reflection class
     * @param object          $object The object
     * @param string          $name   The property name
     *
     * @return mixed
     */
    private function getValue(\ReflectionClass $refl, $object, $name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($object, $getter)) {
            return $object->$getter();
        }

        $prop = $refl->getProperty($name);
        if (!$prop->isPublic()) {
            $prop->setAccessible(true);
        }

        return $prop->getValue($object);
    }
}
