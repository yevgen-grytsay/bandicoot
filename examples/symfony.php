<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 12.10.15
 * Time: 21:41
 */
use Symfony\Component\PropertyAccess\PropertyAccess;
use YevgenGrytsay\Bandicoot\PropertyAccess\SymfonyAdapter;

require_once __DIR__.'/../vendor/autoload.php';

class Person
{
    private $name;

    /**
     * Person constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}

$person = new Person('Yevgen');
$accessor = PropertyAccess::createPropertyAccessor();
echo $accessor->getValue($person, 'name'), PHP_EOL;

$adapter = new SymfonyAdapter($accessor);
echo $adapter->getValue($person, 'name');