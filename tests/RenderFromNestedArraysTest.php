<?php
/**
 * @author: yevgen
 * @date: 10.11.16
 */
namespace YevgenGrytsay\Bandicoot\Tests;

use YevgenGrytsay\Bandicoot\Builder;
use YevgenGrytsay\Bandicoot\Factory;
use YevgenGrytsay\Bandicoot\MergeStrategy\NestedArray;

class RenderFromNestedArraysTest extends \PHPUnit_Framework_TestCase
{
    public function testRender() {
        $dataSource = new \ArrayIterator(array(
            array('jde' => 123456, 'name' => 'Lenovo P780 M&M\'s', 'img' => array('http://image1.jpg', 'http://rsesg.jpg')),
            array('jde' => 98765, 'name' => 'Asus ROG Sica', 'img' => array('http://image2.jpg'))
        ));
        $factory = new Factory(new DefaultPropertyAccess(), new NestedArray());
        $b = new Builder($factory);

        $render = $b->describe([
            'result' => $b->render([
                'product' => $b->each($dataSource, [
                    'jde',
                    'prodname' => 'name',
                    'mticode' => 'jde',
                    'picture' => $b->_list($b->unwindArray('img')),
//            'picture2' => $b->_list($b->unwindArray('img')->each($b->self())),
//            'picture3' => $b->_list($b->unwindArray('img')),
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
        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <root>
              <result>
                <product>
                  <jde>123456</jde>
                  <prodname>Lenovo P780 M&amp;M's</prodname>
                  <mticode>123456</mticode>
                  <picture>http://image1.jpg</picture>
                  <picture>http://rsesg.jpg</picture>
                </product>
                <product>
                  <jde>98765</jde>
                  <prodname>Asus ROG Sica</prodname>
                  <mticode>98765</mticode>
                  <picture>http://image2.jpg</picture>
                </product>
              </result>
            </root>
        ";
        $data = $render();
        $result = XmlHelper::arrayToXml($data);

        self::assertXmlStringEqualsXmlString($expected, $result);
    }
}


class XmlHelper
{
    /**
     * Array -> Xml
     *
     * @param array $data
     * @param string $rootName
     * @param string $version
     * @param string $encoding
     * @return string
     */
    public static function arrayToXml($data, $rootName = 'root', $version = '1.0', $encoding = 'UTF-8')
    {
        $writer = new \DOMDocument($version, $encoding);
        $writer -> formatOutput = true;
        $root = $writer->createElement($rootName);
        if (is_array($data)) {
            self::getXML($writer, $root, $data);
        }
        $writer -> appendChild($root);
        return $writer->saveXML();
    }

    /**
     * @param \DOMDocument $writer
     * @param \DOMElement $root
     * @param array $data
     */
    private static function getXML(\DOMDocument &$writer, \DOMElement &$root, $data)
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
                    $textNode = $writer->createTextNode($val);
                    $element = $writer -> createElement($key);
                    $element->appendChild($textNode);
                }
                $root -> appendChild($element);
            }
        }
    }
}