<?php

class Car {
    public $brand;
    public $color;

    public function intro() {
        echo "The brand of the car is $this->brand and the color is $this->color.\n";
    }
}

class Ferrari extends Car {
    public $speed;
    public function __construct($brand, $color, $speed) {
        $this->brand = $brand;
        $this->color = $color;
        $this->speed = $speed;
    }

    public function intro() {
        echo "The brand of the car is $this->brand, the color is $this->color, and the speed is $this->speed km/h.\n";
    }
}


$ferrari = new Ferrari("Ferrari", "Red", 350);
$ferrari->intro();
?>