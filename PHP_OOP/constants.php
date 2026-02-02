<?php

class Goodbye {
    const MESSAGE = "Goodbye, see you again!";

    public function bye() {
        echo self::MESSAGE . "\n";
    }
}

$goodbye = new Goodbye();
$goodbye->bye();

?>