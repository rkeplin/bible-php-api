<?php
namespace Core\Domain\Entity;

/**
 * AbstractEntity
 *
 * @package CoreDomain\Entity
 * @copyright Rob Keplin 2018
 */
abstract class AbstractEntity
{
    /**
     * Returns an array representation of
     * this variable
     *
     * @return array
     */
    public function toArray()
    {
        $data = array();

        foreach ($this as $key => $value) {
            if (!is_null($value) && !is_object($value)) {
                $method = 'get' . ucfirst($key);

                if (method_exists($this, $method)) {
                    $data[$key] = $this->{$method}();
                }
            }

            if (is_object($value) && ($value instanceof AbstractEntity)) {
                $array = $value->toArray();
                if (count($array) > 0) {
                    $data[$key] = $array;
                }
            }
        }

        return $data;
    }

    /**
     * Populates this object with the object
     * in the first parameter
     *
     * @param $object
     * @return $this|bool
     */
    public function populate($object)
    {
        foreach ($object as $key => $value) {
            $key = str_replace('_', ' ', $key);
            $key = ucwords($key);
            $key = str_replace(' ', '', $key);
            $key = ucfirst($key);

            $methodSet = 'set' . $key;

            if (method_exists($this, $methodSet)) {
                $this->{$methodSet}($value);
            }
        }

        return $this;
    }

    /**
     * Populates this object with the object
     * in the first parameter
     *
     * @param AbstractEntity $entity
     * @return $this
     */
    public function populateFromEntity(AbstractEntity $entity)
    {
        foreach ($this as $key => $value) {
            $methodSet = 'set' . ucfirst($key);
            $methodGet = 'get' . ucfirst($key);

            if (method_exists($entity, $methodGet)) {
                if (method_exists($this, $methodSet)) {
                    $value = $entity->{$methodGet}();
                    if ($value != null) {
                        $this->{$methodSet}($value);
                    }
                }
            }
        }

        return $this;
    }
}
