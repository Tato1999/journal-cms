<?php
    namespace task1\traits\TraitClass;
    trait DiscountTrait {
        
        protected $discount;

        public function setDiscount($discount): void{
            if($discount < 0){
                $discount = 0;
            }
            $this->discount = $discount;
        }
        public function getDiscount(): float{
            return $this->discount;
        }
        public function getDiscountAmount($price):float{
            return ($price * $this->discount) / 100;
        }


    }
?>