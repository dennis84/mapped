<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Transformer\Callback;
use Mapped\Mapping;

/**
 * TransformTo.
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
        $mapping->transform(new Callback(function ($data) use ($object, $mapping) {
            if (is_string($object)) {
                $object = new $object;
            }

            foreach ($mapping->getChildren() as $name => $child) {
                if (array_key_exists($name, $data) && null !== $data[$name]) {
                    $this->setValue($object, $name, $data[$name]);
                }
            }

            return $object;
        }, function ($data) use ($object, $mapping) {
            if (!$data instanceof $object) {
                return;
            }

            $result = [];
            foreach ($mapping->getChildren() as $name => $child) {
                $result[$name] = $this->getValue($data, $name);
            }

            return $result;
        }, false));

        return $mapping;
    }

    /**
     * Sets the given value into the object.
     *
     * @param object $object The object
     * @param string $name   The property name
     * @param mixed  $value  The value
     */
    private function setValue($object, $name, $value)
    {
        $setter = 'set' . ucfirst($name);
        if (method_exists($object, $setter)) {
            return $object->$setter($value);
        }

        throw new \RuntimeException(sprintf(
            'Call to undefined method "%s::%s()"', get_class($object), $setter));
    }

    /**
     * Gets a value from given object.
     *
     * @param object $object The object
     * @param string $name   The property name
     *
     * @return mixed
     */
    private function getValue($object, $name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($object, $getter)) {
            return $object->$getter();
        }

        throw new \RuntimeException(sprintf(
            'Call to undefined method "%s::%s()"', get_class($object), $getter));
    }
}
