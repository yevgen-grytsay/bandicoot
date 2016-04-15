<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date: 15.04.16
 */
use YevgenGrytsay\Bandicoot\Builder;
use YevgenGrytsay\Bandicoot\Factory;
use YevgenGrytsay\Bandicoot\MergeStrategy\FieldArrayMergeStrategy;
use YevgenGrytsay\Bandicoot\MergeStrategy\FieldMergeStrategy;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeEachStrategy;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/test_helper.php';


$productList = array(
    array('jde' => 1000, 'name' => 'Lenovo P780'),
    array('jde' => 1001, 'name' => 'Asus ROG Sica'),
    array('jde' => 1002, 'name' => 'Logitech')
);
$storeList = array(
    array('ext_id' => 'W600', 'entity_id' => 24),
    array('ext_id' => 'W330', 'entity_id' => 141),
    array('ext_id' => 'W300', 'entity_id' => 26),
);
$amountMap = array(
    1000 => array(24 => 1, 141 => 12, 26 => 0),
    1001 => array(24 => 3, 141 => 0, 26 => 6),
    1002 => array(24 => 0, 141 => 12, 26 => 7),
);

$merge = new FieldMergeStrategy();
$nestedMerge = new FieldArrayMergeStrategy();
$listMerge = new MergeEachStrategy($nestedMerge);
$factory = new Factory(new \YevgenGrytsay\Bandicoot\tests\test_helper\DefaultPropertyAccess(), $merge, $listMerge);
$b = new Builder($factory);

class StoreFilterFactory implements \YevgenGrytsay\Bandicoot\ContextFilterFactory
{
    /**
     * @var array
     */
    private $amountMap;

    /**
     * StoreFilterFactory constructor.
     * @param array $amountMap
     */
    public function __construct(array $amountMap)
    {
        $this->amountMap = $amountMap;
    }

    /**
     * @param $contextData
     * @param \YevgenGrytsay\Bandicoot\StackSearch $search
     * @return \YevgenGrytsay\Bandicoot\ContextFilter
     */
    public function createFilter($contextData, \YevgenGrytsay\Bandicoot\StackSearch $search)
    {
        $product = $search->closest('product');
        $id = $product['jde'];

        return new StoreFilter($this->amountMap[$id]);
    }
}
class StoreFilter implements \YevgenGrytsay\Bandicoot\ContextFilter
{
    /**
     * @var array
     */
    private $amountMap;

    /**
     * StoreFilter constructor.
     * @param array $amountMap
     */
    public function __construct(array $amountMap)
    {
        $this->amountMap = $amountMap;
    }

    /**
     * @param $store
     * @return bool
     */
    public function accept($store)
    {
        return $this->hasAmountInStore($store['entity_id']);
    }

    /**
     * @param $storeId
     * @return bool
     */
    private function hasAmountInStore($storeId)
    {
        return array_key_exists($storeId, $this->amountMap) && $this->amountMap[$storeId] > 0;
    }
}

$getAmount = function($store, \YevgenGrytsay\Bandicoot\StackSearch $search) use($amountMap) {
    $product = $search->closest('product');
    $storeId = $store['entity_id'];
    $productId = $product['jde'];

    return $amountMap[$productId][$storeId];
};

$filterFactory = new StoreFilterFactory($amountMap);
$render = $b->describe([
    'products' => $b->render([
        'product' => $b->each(new ArrayIterator($productList), [
            'jde',
            'name',
            'stores' => $b->render([
                'store' => $b->eachFilter(new ArrayIterator($storeList), $filterFactory, array(
                    'ext_id',
                    'amount' => $getAmount
                ))
            ])
        ])
    ])
]);

$result = $render();
$xmlDoc = new SimpleXMLElement("<?xml version=\"1.0\"?><root></root>");
$converter = new \YevgenGrytsay\Bandicoot\tests\test_helper\ArrayToXml();
$converter->convert($result, $xmlDoc);

$xml_file = $xmlDoc->asXML();
$dom = new DOMDocument("1.0");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml_file);
echo $dom->saveXML();