<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 10/3/15
 * Time: 2:13 AM
 */
use YevgenGrytsay\Bandicoot\Builder;
use YevgenGrytsay\Bandicoot\Factory;
use YevgenGrytsay\Bandicoot\MergeStrategy\SimpleArray;

require_once __DIR__.'/../vendor/autoload.php';

$productList = array(
    array('jde' => 123456, 'name' => 'Lenovo P780', 'img' => array('http://image1.jpg', 'http://rsesg.jpg'), 'control' => 0),
    array('jde' => 98765, 'name' => 'Asus ROG Sica', 'img' => array('http://image2.jpg'), 'control' => 1)
);
$productList_2 = array(
    array('jde' => 321564, 'name' => 'Logitech G500s', 'img' => array('http://image3.jpg', 'http://rsesg_2.jpg')),
    array('jde' => 6547, 'name' => 'Samsung Galaxy S6', 'img' => array('http://image4.jpg'))
);
$dataSource = new ArrayIterator($productList);
$priceMap = array(123456 => array('value' => 1200), 98765 => array('value' => 1300),
    321564 => array('value' => 56.8), 6547 => array('value' => 120.6));
$priceFlatMap = array(123456 => '1200.50', 98765 => '1300.50', 321564 => '56.8', 6547 => '120.60');
$storeMap = array(
    24 => array('ext_id' => 'W600', 'amount' => 20),
    141 => array('ext_id' => 'W330', 'amount' => 0),
);

$groups = array(
    array('name' => 'Printers', 'products' => $productList),
    array('name' => 'Phones', 'products' => $productList_2),
);


class ArrayToXml
{
    const TYPE_NUMERIC = 'numeric';
    const TYPE_STRING = 'string';

    /**
     * @param                   $array
     * @param \SimpleXMLElement $el
     * @throws \RuntimeException
     */
    public function convert($array, SimpleXMLElement $el)
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

//function array_to_xml($array, SimpleXMLElement $xmlDoc) {
//    foreach($array as $key => $value) {
//        if(is_array($value)) {
//            $keys = array_keys($value);
//            if (is_numeric($keys[0])) {
//                foreach ($value as $item) {
//                    array_to_xml(array($key => $item), $xmlDoc);
//                }
//            } else {
//                $subnode = $xmlDoc->addChild("$key");
//                array_to_xml($value, $subnode);
//            }
//        } else {
//            $xmlDoc->addChild("$key", htmlspecialchars("$value"));
//        }
//    }
//}

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

class DefaultPropertyAccess implements \YevgenGrytsay\Bandicoot\PropertyAccess
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

$factory = new Factory(new DefaultPropertyAccess(), new SimpleArray());
$factory->setHelperResolver(array(
    'cdata' => function($value) {
        return sprintf('<![CDATA[%s]]>', $value);
    }
));
$b = new Builder($factory);

$render = $b->describe([
    'input_value',
    'result' => $b->render([
        'input_value',
        'productgroup' => $b->each(new ArrayIterator($groups), array(
            'name',
            'products' => $b->render(array(
                'product' => $b->eachUnwind('products', array(
                    'input_value' => function($product, \YevgenGrytsay\Bandicoot\StackSearch $search) {
                        $data = $search->closest('root');

                        return $data['input_value'];
                    },
                    'control' => $b->ifEquals('control', 1, 'Y', 'N'),
                    'jde',
                    'prodname' => 'name',
                    'mticode' => 'jde',
                    'no_value' => 'im_not_here',
//            'picture' => $b->_list($b->unwindArray('img')),
//            'picture2' => $b->_list($b->unwindArray('img')->each($b->self())),
                    'picture3' => $b->_list($b->unwindArray('img')),
                    'price' => [$b->fromMap($priceMap, 'jde', 'value'), ['cdata']],
                    'price2' => $b->fromMap($priceFlatMap, 'jde'),
                    'callable' => function($product) {
                        return '_'.$product['jde'].'_';
                    },
                    'stores' => $b->render(array(
                        'store' => $b->each(new ArrayIterator($storeMap), array(
                            'prod' => function($store, \YevgenGrytsay\Bandicoot\StackSearch $search) {
                                $product = $search->closest('product');

                                return sprintf('[%s][%s]', $store['ext_id'], $product['name']);
                            },
                            'amount' => $b->ifElse('amount', '+', '-')
                        ))
                    )),
                    'constant' => $b->constant('always the same value')
                ))
            ))
        ))
    ])
]);

$result = $render(['input_value' => 'Hello']);
var_dump($result);

//creating object of SimpleXMLElement
$xmlDoc = new SimpleXMLElement("<?xml version=\"1.0\"?><root></root>");
$converter = new ArrayToXml();
//function call to convert array to xml
//array_to_xml($result, $xmlDoc);

$converter->convert($result, $xmlDoc);

//saving generated xml file
$xml_file = $xmlDoc->asXML();
$dom = new DOMDocument("1.0");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml_file);
echo $dom->saveXML();