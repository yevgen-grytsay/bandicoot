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

$b = new \YevgenGrytsay\Bandicoot\Builder();
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

$render = $b->render(array(
    'root' => $b->iterate($dataSource)->render(array(
        'product' => $b->render(array(
            'prodname' => $b->value('name'),
            'mticode' => $b->value('jde'),
            'picture' => $b->_list($b->unwindArray('img')->render($b->self())),
            'picture2' => $b->_list($b->unwindArray('img')->render($b->self()))
        ))
    ))
));


//$render = $b->iterate($dataSource)->render(array(
//    'prodname' => $b->value('name'),
//    'mticode' => $b->value('jde'),
//    'picture' => $b->_list($b->unwindArray('img')->render($b->self())),
//    'picture2' => $b->_list($b->unwindArray('img')->render($b->self()))
//))->merge('array');
$result = $render->run(null);

var_dump($result);