<?php


class Products
{
    public string $name;
    public int $price;
    public int $quantity;

    public function __construct($name, $price, $quantity)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        
    }

    public function get_info()
    {   
        // foreach($all_products as $product) {
            return "Product Name: " . $this->name . ", Price: " . $this->price . ", Quantity: " . $this->quantity;
        // }
        
    }

}

class OrderList
{
    public $products;
    public int $quantity;

    public function __construct( $products, int $quantity)
    {
        $this->products = $products;
        $this->quantity = $quantity;
    }

    public function get_info()
    {
        return "Order Quantity: " . $this->quantity;
    }
}

class Order extends Products
{
    private $items = [];

    public function add_item(OrderList $orderList)
    {
        $this->items[] = $orderList;
    }
    public function getTotal()
    {   
        $total = 0;
        foreach($this->items as $items){
            $total += $items->products * $items->quantity;
        }
    }

}



?>