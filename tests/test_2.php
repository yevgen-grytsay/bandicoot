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

function array_to_xml($array, SimpleXMLElement $xmlDoc) {
    foreach($array as $key => $value) {
        if(is_array($value)) {
            $keys = array_keys($value);
            if (is_numeric($keys[0])) {
                foreach ($value as $item) {
                    array_to_xml(array($key => $item), $xmlDoc);
                }
            } else {
                $subnode = $xmlDoc->addChild("$key");
                array_to_xml($value, $subnode);
            }
        } else {
            $xmlDoc->addChild("$key", htmlspecialchars("$value"));
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
$nestedMerge = new \YevgenGrytsay\Bandicoot\MergeStrategy\FieldArrayMergeStrategy();

$listMerge = new \YevgenGrytsay\Bandicoot\MergeStrategy\MergeEachStrategy($nestedMerge);
$factory = new \YevgenGrytsay\Bandicoot\Factory(new DefaultPropertyAccess(), $merge, $listMerge);
$b = new \YevgenGrytsay\Bandicoot\Builder($factory);

$render = $b->render([
    'result' => $b->render([
        'product' => $b->each($dataSource, [
            'jde',
            'prodname' => 'name',
            'mticode' => 'jde',
//            'picture' => $b->_list($b->unwindArray('img')),
//            'picture2' => $b->_list($b->unwindArray('img')->each($b->self())),
            'picture3' => $b->_list($b->unwindArray('img')),
//            'picture4' => $b->_list($b->unwindArray('img')->each($b->render([
//                'pic' => $b->self()
//            ]))),
//            'picture2' => $b->_list()
//            'picture' => $b->unwindArray('img')->renderArray($b->self()),

            //'picture2' => 'list(unwindArray(img)|renderArray(:self))'
            //'picture2' => $b->_list($b->unwindArray('img')->render($b->self()))
        ])
    ])
]);

$result = $render->run(null);
var_dump($result);

//creating object of SimpleXMLElement
$xmlDoc = new SimpleXMLElement("<?xml version=\"1.0\"?><root></root>");

//function call to convert array to xml
array_to_xml($result, $xmlDoc);

//saving generated xml file
$xml_file = $xmlDoc->asXML();
var_dump($xml_file);