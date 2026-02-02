<?php
interface Animal {
    public function makeSound();
}

class Cat implements Animal {
    public function makeSound() {
        return "Meow\n";
    }
}

$cat = new Cat();
echo $cat->makeSound();

?>
