<?php
namespace task1\classes\Classes;


include __DIR__ .'/../traits/TraitClass.php';


use task1\abstract\AbstractProduct;
use task1\traits\TraitClass\DiscountTrait;
// class TestTraitClass {
//     use DiscountTrait;
// }

class DigitalProducts extends AbstractProduct {
    use DiscountTrait;

    public static $taxValue = 10;
    public function calculateTax():float{
        return (($this->price) * (self::$taxValue) / 100);
    }
    public function getFinalPrice(): float{
        $discount = $this->getDiscountAmount($this->price);
        return ($this->price-($discount + $this->calculateTax()))*$this->quantity;
    }
    public function getDescription(): string{
        return "Digital Product: " . $this->name . ", Price: " . $this->price . " ";
    }

    public function getTax(): float{
        return self::$taxValue;
    }
}

class PhysicalProducts extends AbstractProduct {
    use DiscountTrait;
    public float $weight;
    public static $taxValue = 10;

    public function __construct($id, $name, $price, $quantity, $weight){
        parent::__construct($id, $name, $price, $quantity);
        $this->weight = $weight;
    }
    public function calculateTax():float{
        return $this->price * self::$taxValue / 100;
    }

    public function getFinalPrice(): float{
        $discount = $this->getDiscountAmount($this->price);
        return ($this->price-($discount + $this->calculateTax()))*$this->quantity;
    }
    public function getDescription(): string{
        return "Digital Product: " . $this->name . ", Price: " . $this->price . " ";
    }
    public function getTax(): float{
        return self::$taxValue;
    }
}

?>