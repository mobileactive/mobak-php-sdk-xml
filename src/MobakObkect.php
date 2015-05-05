<?php

namespace mobak;

/**
 * Class MobakObject
 * @package Mobak
 */
class MobakObject
{
    /**
     * @var array - Holds the raw associative data for this object
     */
    protected $backingData;

    /**
     * Creates a GraphObject using the data provided.
     *
     * @param array $raw
     */
    public function __construct($raw)
    {
        if ($raw instanceof \stdClass) {
            $raw = get_object_vars($raw);
        }

        $this->backingData = $raw;
    }

    /**
     * asArray - Return a key-value associative array for the given mobak object.
     *
     * @return array
     */
    public function asArray()
    {
        return $this->backingData;
    }

    /**
     * getProperty - Gets the value of the named property for this mobak object,
     *
     * @param string $name The property to retrieve
     *
     * @return mixed
     */
    public function getProperty($name)
    {
        if (isset($this->backingData[$name])) {
            $value = $this->backingData[$name];
            if (is_scalar($value)) {
                return $value;
            } else {
                return (new MobakObject($value));
            }
        } else {
            return null;
        }
    }

    /**
     * getPropertyNames - Returns a list of all properties set on the object.
     *
     * @return array
     */
    public function getPropertyNames()
    {
        return array_keys($this->backingData);
    }

    /**
     * Returns the string class name of the MobakObject or subclass.
     *
     * @return string
     */
    public static function className()
    {
        return get_called_class();
    }
}