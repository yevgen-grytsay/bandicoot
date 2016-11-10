<?php
/**
 * @author: yevgen
 * @date: 10.11.16
 */
namespace YevgenGrytsay\Bandicoot\Tests;

use YevgenGrytsay\Bandicoot\Builder;
use YevgenGrytsay\Bandicoot\Factory;
use YevgenGrytsay\Bandicoot\MergeStrategy\SimpleArray;

class RenderFromSimpleArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testRender() {
        $productList = array(
            array('jde' => 123456, 'name' => 'Lenovo P780', 'img' => array('http://image1.jpg', 'http://rsesg.jpg'), 'control' => 0),
            array('jde' => 98765, 'name' => 'Asus ROG Sica', 'img' => array('http://image2.jpg'), 'control' => 1)
        );
        $productList_2 = array(
            array('jde' => 321564, 'name' => 'Logitech G500s', 'img' => array('http://image3.jpg', 'http://rsesg_2.jpg')),
            array('jde' => 6547, 'name' => 'Samsung Galaxy S6', 'img' => array('http://image4.jpg'))
        );
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
                'productgroup' => $b->each(new \ArrayIterator($groups), array(
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
                                'store' => $b->each(new \ArrayIterator($storeMap), array(
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
        $expected = "<?xml version=\"1.0\"?>
            <root>
              <input_value>Hello</input_value>
              <result>
                <input_value>Hello</input_value>
                <productgroup>
                  <name>Printers</name>
                  <products>
                    <product>
                      <input_value>Hello</input_value>
                      <control>Y</control>
                      <jde>123456</jde>
                      <prodname>Lenovo P780</prodname>
                      <mticode>123456</mticode>
                      <no_value/>
                      <picture3>http://image1.jpg</picture3>
                      <picture3>http://rsesg.jpg</picture3>
                      <price>&lt;![CDATA[1200]]&gt;</price>
                      <price2>1200.50</price2>
                      <callable>_123456_</callable>
                      <stores>
                        <store>
                          <prod>[W600][Lenovo P780]</prod>
                          <amount>+</amount>
                        </store>
                        <store>
                          <prod>[W330][Lenovo P780]</prod>
                          <amount>-</amount>
                        </store>
                      </stores>
                      <constant>always the same value</constant>
                    </product>
                    <product>
                      <input_value>Hello</input_value>
                      <control>N</control>
                      <jde>98765</jde>
                      <prodname>Asus ROG Sica</prodname>
                      <mticode>98765</mticode>
                      <no_value/>
                      <picture3>http://image2.jpg</picture3>
                      <price>&lt;![CDATA[1300]]&gt;</price>
                      <price2>1300.50</price2>
                      <callable>_98765_</callable>
                      <stores>
                        <store>
                          <prod>[W600][Asus ROG Sica]</prod>
                          <amount>+</amount>
                        </store>
                        <store>
                          <prod>[W330][Asus ROG Sica]</prod>
                          <amount>-</amount>
                        </store>
                      </stores>
                      <constant>always the same value</constant>
                    </product>
                  </products>
                </productgroup>
                <productgroup>
                  <name>Phones</name>
                  <products>
                    <product>
                      <input_value>Hello</input_value>
                      <control>N</control>
                      <jde>321564</jde>
                      <prodname>Logitech G500s</prodname>
                      <mticode>321564</mticode>
                      <no_value/>
                      <picture3>http://image3.jpg</picture3>
                      <picture3>http://rsesg_2.jpg</picture3>
                      <price>&lt;![CDATA[56.8]]&gt;</price>
                      <price2>56.8</price2>
                      <callable>_321564_</callable>
                      <stores>
                        <store>
                          <prod>[W600][Logitech G500s]</prod>
                          <amount>+</amount>
                        </store>
                        <store>
                          <prod>[W330][Logitech G500s]</prod>
                          <amount>-</amount>
                        </store>
                      </stores>
                      <constant>always the same value</constant>
                    </product>
                    <product>
                      <input_value>Hello</input_value>
                      <control>N</control>
                      <jde>6547</jde>
                      <prodname>Samsung Galaxy S6</prodname>
                      <mticode>6547</mticode>
                      <no_value/>
                      <picture3>http://image4.jpg</picture3>
                      <price>&lt;![CDATA[120.6]]&gt;</price>
                      <price2>120.60</price2>
                      <callable>_6547_</callable>
                      <stores>
                        <store>
                          <prod>[W600][Samsung Galaxy S6]</prod>
                          <amount>+</amount>
                        </store>
                        <store>
                          <prod>[W330][Samsung Galaxy S6]</prod>
                          <amount>-</amount>
                        </store>
                      </stores>
                      <constant>always the same value</constant>
                    </product>
                  </products>
                </productgroup>
              </result>
            </root>";

        $data = $render(['input_value' => 'Hello']);

        $xmlDoc = new \SimpleXMLElement("<?xml version=\"1.0\"?><root></root>");
        $converter = new ArrayToXml();
        $converter->convert($data, $xmlDoc);
        $xml_file = $xmlDoc->asXML();
        $dom = new \DOMDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml_file);
        $result = $dom->saveXML();

        self::assertXmlStringEqualsXmlString($expected, $result);
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