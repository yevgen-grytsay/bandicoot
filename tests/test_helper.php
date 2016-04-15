<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date: 15.04.16
 */
namespace YevgenGrytsay\Bandicoot\tests\test_helper;

class ArrayHelper
{
    public static function getValue($array, $key, $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }
        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }
        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }
        if (is_object($array)) {
            return $array->$key;
        } elseif (is_array($array)) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        } else {
            return $default;
        }
    }
}

class DefaultPropertyAccess implements \YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface
{
    /**
     * @param $objectOrArray
     * @param $propertyPath
     *
     * @return mixed
     */
    public function getValue($objectOrArray, $propertyPath)
    {
        return ArrayHelper::getValue($objectOrArray, $propertyPath);
    }
}

class ArrayToXml
{
    const TYPE_NUMERIC = 'numeric';
    const TYPE_STRING = 'string';

    /**
     * @param                   $array
     * @param \SimpleXMLElement $el
     * @throws \RuntimeException
     */
    public function convert($array, \SimpleXMLElement $el)
    {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if (count($value) === 0) {
                    continue;
                }
                if ($this->isNumeric($value)) {
                    foreach ($value as $item) {
                        $this->convert(array($key => $item), $el);
                    }
                } else {
                    $child = $el->addChild("$key");
                    $this->convert($value, $child);
                }
            } else {
                $el->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    /**
     * @param array $arr
     *
     * @return bool
     * @throws \RuntimeException
     */
    private function isNumeric(array $arr)
    {
        $type = $this->checkKeysType($arr);

        return $type === self::TYPE_NUMERIC;
    }

    /**
     * @param array $arr
     *
     * @return string
     * @throws \RuntimeException
     */
    private function checkKeysType(array $arr)
    {
        if (count($arr) === 0) {
            throw new \RuntimeException('Can\'t determine keys type of an empty array.');
        }

        $numeric = 0;
        $string = 0;
        foreach ($arr as $key => $item) {
            if (is_int($key)) {
                ++$numeric;
            } else {
                ++$string;
            }
            if ($numeric > 0 && $string > 0) {
                throw new \RuntimeException(
                    printf('Array should contain keys of only one type (string, integer). Given array
                    contains keys of both types: "%s"', print_r($arr, true)));
            }
        }

        $result = self::TYPE_STRING;
        if ($numeric > 0) {
            $result = self::TYPE_NUMERIC;
        }

        return $result;
    }
}