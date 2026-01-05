<?php
    namespace task1\abstract;

    include __DIR__ .'/../interfaces/InterfaceClass.php';
    
    use task1\interface\interfaceClass\Sellable;


    abstract class AbstractProduct implements Sellable{
        public $id;
        public $name;
        public $price;
        public $quantity;
        const CURRENNCY = "USD";

        public  function __construct($id, $name, $price, $quantity){
            $this->id = $id;
            $this->name = $name;
            $this->price = $price;
            $this->quantity = $quantity;
        }

        public function getName(): string{
            return $this->name;
        }

        public function getPrice(): int{
            return $this->price;
        }


        abstract function calculateTax(): float;
    }
?>


