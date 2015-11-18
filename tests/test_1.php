<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 10/3/15
 * Time: 2:13 AM
 */
require_once __DIR__.'/../vendor/autoload.php';

$dataSource = new ArrayIterator(array(
    array('jde' => 123456, 'name' => 'Lenovo P780', 'img' => array('http://image1.jpg', 'http://rsesg.jpg')),
    array('jde' => 98765, 'name' => 'Asus ROG Sica', 'img' => array('http://image2.jpg'))
));

class A1_Helper_Xml
{
    /**
     * Array -> Xml
     *
     * @param array $data
     * @param String $rootName
     * @param String $version
     * @param String $encoding
     * @return string
     */
    public static function arrayToXml($data, $rootName = 'root', $version = '1.0', $encoding = 'UTF-8')
    {
        $writer = new DOMDocument($version, $encoding);
        $writer -> formatOutput = true;
        $root = $writer->createElement($rootName);
        if (is_array($data)) {
            self::getXML($writer, $root, $data);
        }
        $writer -> appendChild($root);
        return $writer->saveXML();
    }

    /**
     * @param DOMDocument $writer
     * @param DOMElement $root
     * @param array $data
     */
    private static function getXML(DOMDocument &$writer, DOMElement &$root, $data)
    {
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                if (is_numeric($key)) {
                    self::getXML($writer, $root, $val);
                } else {
                    $child = $writer -> createElement($key);
                    self::getXML($writer, $child, $val);
                    $root -> appendChild($child);
                }
            } else {
                if (strpos($val, '<![CDATA[') !== false) {
                    $val = str_replace(array('<![CDATA[',']]>'), '', $val);
                    $element = $writer -> createElement($key);
                    $element -> appendChild($writer -> createCDATASection($val));
                } else {
                    $element = $writer -> createElement($key,  $val);
                }
                $root -> appendChild($element);
            }
        }
    }
}

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

$merge = new \YevgenGrytsay\Bandicoot\MergeStrategy\FieldMergeStrategy();
$nestedMerge = new \YevgenGrytsay\Bandicoot\MergeStrategy\NestedArrayMergeStrategy();

$listMerge = new \YevgenGrytsay\Bandicoot\MergeStrategy\MergeEachStrategy($nestedMerge);
$factory = new \YevgenGrytsay\Bandicoot\Factory(new DefaultPropertyAccess(), $merge, $listMerge);
$b = new \YevgenGrytsay\Bandicoot\Builder($factory);

$render = $b->render([
    'result' => $b->render([
        'product' => $b->each($dataSource, [
            'jde',
            'prodname' => 'name',
            'mticode' => 'jde',
            'picture' => $b->_list($b->unwindArray('img')),
            'picture2' => $b->_list($b->unwindArray('img')->each($b->self())),
//            'picture2' => $b->_list()
//            'picture' => $b->unwindArray('img')->renderArray($b->self()),

            //'picture2' => 'list(unwindArray(img)|renderArray(:self))'
            //'picture2' => $b->_list($b->unwindArray('img')->render($b->self()))
        ])
    ])
]);

$result = $render->run(null);
var_dump($result);

$result = A1_Helper_Xml::arrayToXml($result);
var_dump($result);