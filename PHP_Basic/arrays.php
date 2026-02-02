<?php

$cars = array("Volvo", 15, ["apples", "bananas"]);

// Menghitung jumlah elemen dalam array
echo count($cars);

// Mengakses elemen array
echo $cars[1] . "\n";

// Mengganti nilai dari elemen array
$cars[0] = "Toyota";
echo $cars[0] . "\n";

// Loop melalui elemen array
foreach ($cars as $x) {
    if (is_array($x)) {
        foreach ($x as $y) {
            echo $y . "\n";
        }
    } else{
        echo $x . "\n";
    }
}

// Array asosiatif
$biodata = array(
    "name" => "Panji Angkasa",
    "age" => 19
);

echo "Name: " . $biodata["name"] . "\n";

// Loop melalui array asosiatif
foreach ($biodata as $x => $y) {
    echo "$x: $y\n";
}
?>