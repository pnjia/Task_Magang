<?php

trait message1 {
    public function msg1() {
        return "OOP is fun! \n";
    }

    public function msg2() {
        return "OOP stands for Object Oriented Programming \n";
    }

    public function msg3() {
        return "I love PHP \n";
    }
}

class Welcome {
    use message1;
}

class Welcome2 {
    use message1;
}

$obj = new Welcome();
echo $obj->msg1();

$obj2 = new Welcome2();
echo $obj2->msg1();
echo $obj2->msg2();
echo $obj2->msg3();

?>