<?php

class Car {
    public $brand;
    public $color;

    public function intro() {
        echo "The brand of the car is $this->brand and the color is $this->color.\n";
    }
}

class Ferrari extends Car {
    public function message() {
        echo "This is a Ferrari car.\n";
    }
}


$ferrari = new Ferrari();
$ferrari->intro();
$ferrari->message();
?>