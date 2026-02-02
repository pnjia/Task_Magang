<?php

class Fruit {
    public $name;
    public $color;

    function set_details($name, $color) {
        $this->name = $name;
        $this->color = $color;
    }

    function get_details() {
        echo "Name: " . $this->name . ". Color: " . $this->color . "\n";
    }
}

$apple = new Fruit();
$apple->set_details("Apple", "Red");
$apple->get_details();

$banana = new Fruit();
$banana->set_details("Banana", "Yellow");
$banana->get_details();

?>