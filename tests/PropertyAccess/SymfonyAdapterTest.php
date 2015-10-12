<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date: 12.10.15
 */

namespace YevgenGrytsay\Bandicoot\Tests\PropertyAccess;


use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use YevgenGrytsay\Bandicoot\PropertyAccess\SymfonyAdapter;

class SymfonyAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldCallSymfonyAccessorWithProperArguments()
    {
        $object = new \stdClass();
        $accessor = $this->getMock(PropertyAccessorInterface::class);
        $accessor->expects($this->once())
            ->method('getValue')
            ->with($object, 'field');

        $adapter = new SymfonyAdapter($accessor);
        $adapter->getValue($object, 'field');
    }

    public function testShouldReturnValue()
    {
        $accessor = $this->getMock(PropertyAccessorInterface::class);
        $accessor->expects($this->once())
            ->method('getValue')
            ->willReturn('value');

        $adapter = new SymfonyAdapter($accessor);
        $value = $adapter->getValue(null, null);

        $this->assertEquals('value', $value);
    }
}
