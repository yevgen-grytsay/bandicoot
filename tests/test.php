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
$listMerge = new \YevgenGrytsay\Bandicoot\MergeStrategy\NestedArrayMergeStrategy();
$factory = new \YevgenGrytsay\Bandicoot\Factory(new DefaultPropertyAccess(), $merge, $listMerge);

$b = new \YevgenGrytsay\Bandicoot\Builder($factory);
//$render = $b->render(array(
//    'root' => $b->render(array(
//        'product' => $b->iterate($dataSource)->render(array(
//            'prodname' => $b->value('name'),
//            'mticode' => $b->value('jde'),
//            'picture' => $b->_list($b->unwindArray('img')->render($b->self())),
//            'picture2' => $b->_list($b->unwindArray('img')->render($b->self()))
//        ))->merge('array')
//    ))->merge('array')
//));


$pushMerge = new \YevgenGrytsay\Bandicoot\MergeStrategy\ArrayPushMergeStrategy();

$render = $b->render(array(
    'root' => $b->each($dataSource, $b->render(array(
        'product' => $b->render(array(
            'prodname' => $b->value('name'),
            'mticode' => $b->value('jde'),
            'picture' => $b->_list($b->unwindArray('img')->renderArray($b->self()))->merge($pushMerge),
//            'picture2' => $b->_list()
//            'picture' => $b->unwindArray('img')->renderArray($b->self()),

            //'picture2' => 'list(unwindArray(img)|renderArray(:self))'
            //'picture2' => $b->_list($b->unwindArray('img')->render($b->self()))
        ))
    )))
));


//$render = $b->iterate($dataSource)->render(array(
//    'prodname' => $b->value('name'),
//    'mticode' => $b->value('jde'),
//    'picture' => $b->_list($b->unwindArray('img')->render($b->self())),
//    'picture2' => $b->_list($b->unwindArray('img')->render($b->self()))
//))->merge('array');
$result = $render->run(null);

var_dump($result);