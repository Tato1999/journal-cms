<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
// include __DIR__. '/task1/interfaces/InterfaceClass.php';
include __DIR__ .'/task1/abstract/AbstractProduct.php';

include __DIR__ .'/task1/classes/Classes.php';


use task1\classes\Classes\DigitalProducts;
use task1\classes\Classes\PhysicalProducts;
use task1\traits\DiscountTrait;

$item = new PhysicalProducts(1,"Laptop", 1000, 1, 2.5);
$item->setDiscount(10);
function getProductInfo($item){
    echo $item->getName()." <br>";
    echo $item->getPrice()." <br>";
    echo $item->getTax() ."%"." <br>";
    echo $item->getFinalPrice().$item::CURRENNCY." <br>";
    echo $item->getDescription()." <br>";
}

echo getProductInfo($item);
?>