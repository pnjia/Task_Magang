<?php

class Fruit {
    public $name;
    public $color;

    public function __construct($name, $color) {
        $this->name = $name;
        $this->color = $color;
    }
    
    function __destruct() {
        echo "Name: " . $this->name . " Color: " . $this->color . "\n";
    }
}

$apple = new Fruit("Apple", "Red");
$banana = new Fruit("Banana", "Yellow");

?>