<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date: 13.04.16
 */
use YevgenGrytsay\Bandicoot\Builder;
use YevgenGrytsay\Bandicoot\Factory;
use YevgenGrytsay\Bandicoot\MergeStrategy\FieldArrayMergeStrategy;
use YevgenGrytsay\Bandicoot\MergeStrategy\FieldMergeStrategy;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeEachStrategy;

require_once __DIR__.'/../vendor/autoload.php';

$faker = \Faker\Factory::create();
$productCount = 4000;
$productList = new ArrayObject();
foreach (range(1, $productCount) as $item) {
    $productList->append(array(
        'jde' => $faker->unique()->numberBetween(1, pow(10, 6)),
        'name' => $faker->name,
        'img' => array(
            $faker->url
        )
    ));
}
$storeMap = array(
    24 => array('ext_id' => 'W600'),
    26 => array('ext_id' => 'W300'),
    141 => array('ext_id' => 'W330'),
    142 => array('ext_id' => 'W630'),
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

$merge = new FieldMergeStrategy();
$nestedMerge = new FieldArrayMergeStrategy();
$listMerge = new MergeEachStrategy($nestedMerge);
$factory = new Factory(new DefaultPropertyAccess(), $merge, $listMerge);
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
        'product' => $b->each($productList->getIterator(), [
            'input_value' => function($product, \YevgenGrytsay\Bandicoot\StackSearch $search) {
                $data = $search->closest('root');

                return $data['input_value'];
            },
            'jde',
            'prodname' => 'name',
            'mticode' => 'jde',
            'no_value' => 'im_not_here',
            'picture3' => $b->_list($b->unwindArray('img')),
            'callable' => function($product) {
                return '_'.$product['jde'].'_';
            },
            'stores' => $b->render(array(
                'store' => $b->each(new ArrayIterator($storeMap), array(
                    'prod' => function($store, \YevgenGrytsay\Bandicoot\StackSearch $search) {
                        $product = $search->closest('product');

                        return sprintf('[%s][%s]', $store['ext_id'], $product['name']);
                    }
                ))
            ))
        ])
    ])
]);

$timeStart = time();
$result = $render(['input_value' => 'Hello']);
$timeEnd = time();
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

echo sprintf('Rendering took %d sec.', $timeEnd - $timeStart);