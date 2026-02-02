<?php
$hari = 2;

$output = match($hari) {
    1 => "Senin",
    2 => "Selasa",
    3 => "Rabu",
    4 => "Kamis",
    5 => "Jumat",
    6 => "Sabtu",
    7 => "Minggu",
    default => "Hari tidak valid",
};

echo $output;

?>