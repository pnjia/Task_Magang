<?php

abstract class Car {
    public $name;

    public function __construct($name) {
        $this->name = $name;
    }

    abstract public function intro();
}

class Audi extends Car {
    public function intro() {
        return "German quality! I am an $this->name.\n";
    }
}

class Citroen extends Car {
    public function intro() {
        return "French elegance! I am a $this->name.\n";
    }
}

$audi = new Audi("Audi");
echo $audi->intro();

$citroen = new Citroen("Citroen");
echo $citroen->intro();

?>